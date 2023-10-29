<?php

namespace MiniBus\Test\Transport\Serializer;

use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Test\StubMessage;
use MiniBus\Transport\Serializer\JsonSerializer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Transport\Serializer\JsonSerializer
 *
 * @internal
 */
final class JsonSerializerTest extends TestCase
{
    public function testItDoesEncodeAsJson()
    {
        $subject = 'some-subject';

        $envelope = new BasicEnvelope(
            new StubMessage($subject, ['key' => 'value'], ['another-key' => 'another-value']),
            new StampCollection([])
        );

        $normalizer = new StubNormalizer([
            'headers' => [
                'subject' => $subject,
                'key' => 'value',
            ],
            'body' => [
                'another-key' => 'another-value',
            ],
        ]);

        $actualJson = (new JsonSerializer($normalizer))->serialize($envelope);
        static::assertJson($actualJson);

        $expected = [
            'headers' => [
                'subject' => $subject,
                'key' => 'value',
            ],
            'body' => [
                'another-key' => 'another-value',
            ],
        ];

        static::assertEquals($expected, json_decode($actualJson, true));
    }
}
