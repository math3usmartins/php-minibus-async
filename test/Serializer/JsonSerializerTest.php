<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Serializer;

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
        $message = new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
        $actualJson = (new JsonSerializer())->execute($message);
        static::assertJson($actualJson);

        $expected = [
            'headers' => [
                'subject' => 'some-subject',
                'header' => 'h',
            ],
            'body' => ['body' => 'v'],
        ];

        static::assertEquals($expected, json_decode($actualJson, true));
    }
}
