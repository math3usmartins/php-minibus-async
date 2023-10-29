<?php

namespace MiniBus\Test\Transport\Unserializer;

use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Test\StubMessage;
use MiniBus\Test\Transport\Unserializer\Denormalizer\StubDenormalizer;
use MiniBus\Test\Transport\Unserializer\Denormalizer\StubDenormalizerLocator;
use MiniBus\Transport\Sender\SenderStamp;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerLocator;
use MiniBus\Transport\Unserializer\JsonUnserializer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Transport\Unserializer\JsonUnserializer
 *
 * @internal
 */
final class JsonUnserializerTest extends TestCase
{
    /**
     * @param string $rawMessage
     *
     * @dataProvider scenarios
     */
    public function testUnserialize(
        $rawMessage,
        DenormalizerLocator $denormalizerLocator,
        Envelope $expected
    ) {
        $unserializer = new JsonUnserializer($denormalizerLocator);

        static::assertEquals($expected, $unserializer->unserialize($rawMessage));
    }

    public function scenarios()
    {
        $subject = 'some-subject';
        $headers = ['foo' => 'bar'];
        $body = ['given' => 'value'];

        $data = [
            'headers' => $headers,
            'body' => $body,
        ];

        $stamps = new StampCollection([
            new SenderStamp(),
        ]);

        $envelope = new BasicEnvelope(
            new StubMessage($subject, $headers, $body),
            $stamps
        );

        $message = new StubMessage($subject, $headers, $body);

        yield 'succesful ' => [
            'raw message' => json_encode($data),
            'denormalizer locator' => new StubDenormalizerLocator(new StubDenormalizer($envelope)),
            'expected' => new BasicEnvelope($message, $stamps),
        ];
    }
}
