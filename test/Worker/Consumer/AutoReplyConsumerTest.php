<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Worker\Consumer;

use Closure;
use Exception;
use MiniBus\Dispatcher\DefaultDispatcher;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\BasicEnvelopeFactory;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Middleware\MiddlewareStack;
use MiniBus\Test\Envelope\Stamp\StubStamp;
use MiniBus\Test\Middleware\FailingMiddleware;
use MiniBus\Test\StubMessage;
use MiniBus\Test\Transport\Receiver\InMemoryReceiver;
use MiniBus\Test\Transport\Worker\Consumer\RetryStrategy\CallbackRetryStrategy;
use MiniBus\Transport\Receiver;
use MiniBus\Transport\Sender\SenderStamp;
use MiniBus\Transport\Worker\Consumer\AutoReplyConsumer;
use MiniBus\Transport\Worker\Consumer\Stamp\FailedStamp;
use MiniBus\Transport\Worker\Consumer\Stamp\RetriableStamp;
use MiniBus\Transport\Worker\Consumer\Stamp\SuccessfulStamp;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Dispatcher\DefaultDispatcher
 * @covers \MiniBus\Envelope\EnvelopeCollection
 * @covers \MiniBus\Test\Transport\Receiver\InMemoryReceiver
 * @covers \MiniBus\Transport\Worker\Consumer\AutoReplyConsumer
 *
 * @internal
 */
final class AutoReplyConsumerTest extends TestCase
{
    /**
     * @dataProvider provideConsumeCases
     */
    public function testConsume(
        AutoReplyConsumer $consumer,
        Receiver $receiver,
        EnvelopeCollection $expectedEnvelopes,
        Closure $receiverAssertionCallback,
    ): void {
        self::assertEquals($expectedEnvelopes, $consumer->consume($receiver));

        $receiverAssertionCallback($receiver);
    }

    public function provideConsumeCases(): iterable
    {
        $exception = new Exception('something went wrong');
        $dispatcher = new DefaultDispatcher(
            new BasicEnvelopeFactory(),
            new MiddlewareStack([
                new FailingMiddleware($exception),
            ]),
        );

        $envelope = new BasicEnvelope(
            new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']),
            new StampCollection([
                new StubStamp('key', 'value'),
            ]),
        );

        $anotherEnvelope = new BasicEnvelope(
            new StubMessage('another-subject', ['another-header' => 'h'], ['another-body' => 'b']),
            new StampCollection([
                new StubStamp('another-key', 'another-value'),
            ]),
        );

        $envelopes = new EnvelopeCollection([
            $envelope,
            $anotherEnvelope,
        ]);

        $receiver = new InMemoryReceiver($envelopes);

        yield 'never retry' => [
            'consumer' => new AutoReplyConsumer(
                $dispatcher,
                new CallbackRetryStrategy(
                    static fn (Envelope $envelope) => $envelope,
                ),
            ),
            'receiver' => $receiver,
            'expected' => InMemoryReceiver::identifiable($envelopes)->map(
                static fn (Envelope $envelope) => $envelope
                    // it MUST add SenderStamp to envelopes
                    ->withStamp(new SenderStamp())
                    // it MUST mark envelope as failed
                    ->withStamp(new FailedStamp($receiver, $exception)),
            ),
            'receiver assertion callback' => static function (Receiver $receiver): void {
                // receiver should discard all failing messages
                self::assertEquals([], $receiver->fetch()->items());
            },
        ];

        $receiver = new InMemoryReceiver($envelopes);

        yield 'retry specific one' => [
            'consumer' => new AutoReplyConsumer(
                $dispatcher,
                new CallbackRetryStrategy(
                    static fn (Envelope $envelope) => 'another-subject' === $envelope->message()->subject()
                        ? $envelope->withStamp(new RetriableStamp())
                        : $envelope,
                ),
            ),
            'receiver' => $receiver,
            'expected' => InMemoryReceiver::identifiable($envelopes)->map(
                static fn (Envelope $envelope) => $envelope
                    // it MUST add SenderStamp to envelopes
                    ->withStamp(new SenderStamp())
                    // it MUST mark envelope as failed
                    ->withStamp(new FailedStamp($receiver, $exception)),
            ),
            'receiver assertion callback' => static function (Receiver $receiver) use ($anotherEnvelope): void {
                $anotherEnvelopeWithId = $anotherEnvelope->withStamp(
                    new StubStamp(InMemoryReceiver::IN_MEMORY_ID_STAMP_NAME, '1'),
                );

                // in-memory receiver should keep messages that are retriable
                self::assertEquals([$anotherEnvelopeWithId], $receiver->fetch()->items());
            },
        ];

        $dispatcher = new DefaultDispatcher(
            new BasicEnvelopeFactory(),
            new MiddlewareStack([]),
        );

        $receiver = new InMemoryReceiver($envelopes);

        yield 'successful consuming' => [
            'consumer' => new AutoReplyConsumer(
                $dispatcher,
                new CallbackRetryStrategy(
                    static fn (Envelope $envelope) => $envelope,
                ),
            ),
            'receiver' => $receiver,
            'expected' => InMemoryReceiver::identifiable($envelopes)->map(
                static fn (Envelope $envelope) => $envelope
                    // it MUST add SenderStamp to envelopes
                    ->withStamp(new SenderStamp())
                    // it MUST mark envelope as failed
                    ->withStamp(new SuccessfulStamp()),
            ),
            'receiver assertion callback' => static function (Receiver $receiver): void {
                // in-memory receiver should discard successful messages
                self::assertEquals([], $receiver->fetch()->items());
            },
        ];
    }
}
