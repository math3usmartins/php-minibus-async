<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Unserializer;

use Exception;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\BasicEnvelopeFactory;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Test\StubMessage;
use MiniBus\Test\Transport\Unserializer\Denormalizer\StubDenormalizer;
use MiniBus\Transport\Unserializer\Denormalizer\CompositeDenormalizer;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerNotFoundException;
use MiniBus\Transport\Unserializer\JsonUnserializer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Envelope\BasicEnvelopeFactory
 * @covers \MiniBus\Transport\Unserializer\Denormalizer\CompositeDenormalizer
 * @covers \MiniBus\Transport\Unserializer\JsonUnserializer
 *
 * @internal
 */
final class JsonUnserializerTest extends TestCase
{
    /**
     * @dataProvider provideScenarioCases
     */
    public function testScenario(
        string $rawMessage,
        CompositeDenormalizer $denormalizer,
        mixed $expected,
    ): void {
        $unserializer = new JsonUnserializer(
            $denormalizer,
            new BasicEnvelopeFactory(),
        );

        if ($expected instanceof Exception) {
            self::expectException($expected::class);
            $unserializer->execute($rawMessage);
        } else {
            self::assertEquals($expected, $unserializer->execute($rawMessage));
        }
    }

    public function provideScenarioCases(): iterable
    {
        $subject = 'some-subject';

        $data = [
            'headers' => $headers = ['foo' => 'bar'],
            'body' => $body = ['given' => 'value'],
        ];

        yield 'data not supported' => [
            'raw message' => json_encode($data),
            'denormalizer' => new CompositeDenormalizer([]),
            'expected' => new DenormalizerNotFoundException(),
        ];

        $message = new StubMessage($subject, $headers, $body);
        $expectedEnvelope = new BasicEnvelope($message, new StampCollection([]));

        yield 'data is supported' => [
            'raw message' => json_encode($data),
            'denormalizer' => new CompositeDenormalizer([
                new StubDenormalizer(true, $message),
            ]),
            'expected' => $expectedEnvelope,
        ];
    }
}
