<?php

namespace MiniBus\Test\Transport\Handler;

use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Test\StubMessage;
use MiniBus\Test\Transport\Sender\SpySender;
use MiniBus\Transport\Handler\TransportHandler;
use MiniBus\Transport\Sender\SenderStamp;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Transport\Handler\TransportHandler
 * @covers \MiniBus\Transport\Sender\SenderStamp
 *
 * @internal
 */
final class TransportHandlerTest extends TestCase
{
    /**
     * @dataProvider scenarios
     */
    public function testHandle(
        Envelope $givenEnvelope,
        Envelope $expectedEnvelope
    ) {
        $sender = new SpySender();
        $handler = new TransportHandler($sender);

        static::assertEquals($expectedEnvelope, $handler->handle($givenEnvelope));

        $alreadySent = $givenEnvelope->stamps()->contains(new SenderStamp());

        $expectedSentEnvelopes = $alreadySent
            // scenario: envelope was already sent, MUST NOT be sent again
            ? []
            // scenario: envelope was NOT already sent, MUST be sent now
            : [$givenEnvelope];

        static::assertEquals($expectedSentEnvelopes, $sender->envelopes());
    }

    public function scenarios()
    {
        $subject = 'some-subject';

        $envelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'h'], ['body' => 'v']),
            new StampCollection([])
        );

        $stampedEnvelope = $envelope->withStamp(new SenderStamp());

        yield 'already sent envelope' => [
            'given envelope' => $stampedEnvelope,
            'expected envelope' => $stampedEnvelope,
        ];

        yield 'NOT already sent envelope' => [
            'given envelope' => $envelope,
            'expected envelope' => $stampedEnvelope,
        ];
    }
}
