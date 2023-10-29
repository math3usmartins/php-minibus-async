<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Worker;

use Closure;
use MiniBus\Dispatcher\DefaultDispatcher;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\BasicEnvelopeFactory;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Middleware\MiddlewareStack;
use MiniBus\Test\Envelope\Stamp\StubStamp;
use MiniBus\Test\StubMessage;
use MiniBus\Test\Transport\Receiver\InMemoryReceiver;
use MiniBus\Test\Transport\Worker\Consumer\RetryStrategy\CallbackRetryStrategy;
use MiniBus\Transport\Worker\Consumer\AutoReplyConsumer;
use MiniBus\Transport\Worker\PrioritizedWorker;
use MiniBus\Transport\Worker\StopStrategy\MaxIterationCountStopStrategy;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MiniBus\Transport\Worker\Consumer\AutoReplyConsumer
 * @covers \MiniBus\Transport\Worker\PrioritizedWorker
 * @covers \MiniBus\Transport\Worker\StopStrategy\MaxIterationCountStopStrategy
 */
final class PrioritizedWorkerTest extends TestCase
{
    /**
     * @dataProvider provideRunCases
     */
    public function testRun(
        PrioritizedWorker $worker,
        Closure $assertionCallback,
    ): void {
        $worker->run();
        $assertionCallback();
    }

    public function provideRunCases(): iterable
    {
        $dispatcher = new DefaultDispatcher(
            new BasicEnvelopeFactory(),
            new MiddlewareStack([]),
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
        $anotherReceiver = new InMemoryReceiver($envelopes);

        $consumer = new AutoReplyConsumer(
            $dispatcher,
            new CallbackRetryStrategy(
                static fn (Envelope $envelope) => $envelope,
            ),
        );

        yield 'it must consume all and auto stop after time limit' => [
            'worker' => new PrioritizedWorker(
                new MaxIterationCountStopStrategy(5),
                1,
                $consumer,
                [$receiver, $anotherReceiver],
            ),
            'assertion callback' => static function () use ($receiver, $anotherReceiver): void {
                self::assertEquals([], $receiver->fetch()->items());
                self::assertEquals([], $anotherReceiver->fetch()->items());
            },
        ];

        $receiver = new InMemoryReceiver($envelopes);
        $anotherReceiver = new InMemoryReceiver($envelopes);

        yield 'it must consume from first receiver first' => [
            'worker' => new PrioritizedWorker(
                new MaxIterationCountStopStrategy(1),
                1,
                $consumer,
                [$receiver, $anotherReceiver],
            ),
            'assertion callback' => static function () use ($receiver, $anotherReceiver, $envelopes): void {
                self::assertEquals([], $receiver->fetch()->items());
                self::assertEquals(InMemoryReceiver::identifiable($envelopes), $anotherReceiver->fetch());
            },
        ];
    }
}
