<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Worker\Consumer\RetryStrategy;

use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Test\StubMessage;
use MiniBus\Transport\Worker\Consumer\RetryStrategy\StrategyResponse;
use MiniBus\Transport\Worker\Consumer\Stamp\RetriableStamp;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Envelope\EnvelopeCollection
 * @covers \MiniBus\Transport\Worker\Consumer\RetryStrategy\StrategyResponse
 *
 * @internal
 */
final class StrategyResponseTest extends TestCase
{
    /**
     * @dataProvider provideCheckResponseCases
     */
    public function testCheckResponse(
        EnvelopeCollection $envelopes,
        EnvelopeCollection $expectedRetriable,
        EnvelopeCollection $expectedNotRetriable,
    ): void {
        $response = new StrategyResponse($envelopes);

        self::assertEquals($expectedRetriable, $response->findRetriable());
        self::assertEquals($expectedNotRetriable, $response->findNotRetriable());
    }

    public function provideCheckResponseCases(): iterable
    {
        yield 'empty collection' => [
            'envelopes' => new EnvelopeCollection([]),
            'expected retriable' => new EnvelopeCollection([]),
            'expected not retriable' => new EnvelopeCollection([]),
        ];

        $message = new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
        $envelope = new BasicEnvelope($message, new StampCollection([]));

        $givenCollection = new EnvelopeCollection([
            $envelope->withStamp(new RetriableStamp()),
            $envelope,
        ]);

        yield 'non empty collection' => [
            'envelopes' => $givenCollection,
            'expected retriable' => new EnvelopeCollection([
                $envelope->withStamp(new RetriableStamp()),
            ]),
            'expected not retriable' => new EnvelopeCollection([
                $envelope,
            ]),
        ];
    }
}
