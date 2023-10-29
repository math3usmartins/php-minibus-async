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
    public function testItDoesEncodeAsJson(): void
    {
        $message = new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
        $actualJson = (new JsonSerializer())->execute($message);
        self::assertJson($actualJson);

        $expected = [
            'headers' => [
                'subject' => 'some-subject',
                'header' => 'h',
            ],
            'body' => ['body' => 'v'],
        ];

        self::assertEquals($expected, json_decode($actualJson, true, 512, JSON_THROW_ON_ERROR));
    }
}
