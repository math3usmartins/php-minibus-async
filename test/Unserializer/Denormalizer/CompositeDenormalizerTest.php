<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Unserializer\Denormalizer;

use Exception;
use MiniBus\Test\StubMessage;
use MiniBus\Transport\Unserializer\Denormalizer\CompositeDenormalizer;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Transport\Unserializer\Denormalizer\CompositeDenormalizer
 *
 * @internal
 */
final class CompositeDenormalizerTest extends TestCase
{
    /**
     * @dataProvider provideSupportsMethodCases
     */
    public function testSupportsMethod(
        CompositeDenormalizer $denormalizer,
        array $data,
        mixed $expected,
    ): void {
        self::assertEquals($expected, $denormalizer->supports($data));
    }

    public function provideSupportsMethodCases(): iterable
    {
        $data = [
            'headers' => ['foo' => 'bar'],
            'body' => ['given' => 'value'],
        ];

        yield 'data not supported' => [
            'denormalizer' => new CompositeDenormalizer([
                new StubDenormalizer(false),
            ]),
            'data' => $data,
            'expected' => false,
        ];

        yield 'data is supported' => [
            'denormalizer' => new CompositeDenormalizer([
                new StubDenormalizer(true),
            ]),
            'data' => $data,
            'expected' => true,
        ];
    }

    /**
     * @dataProvider provideExecuteMethodCases
     *
     * @throws DenormalizerNotFoundException
     */
    public function testExecuteMethod(
        CompositeDenormalizer $denormalizer,
        array $data,
        mixed $expected,
    ): void {
        if ($expected instanceof Exception) {
            self::expectException($expected::class);
            $denormalizer->execute($data);
        } else {
            self::assertEquals($expected, $denormalizer->execute($data));
        }
    }

    public function provideExecuteMethodCases(): iterable
    {
        $subject = 'some-subject';

        $data = [
            'headers' => $headers = ['foo' => 'bar'],
            'body' => $body = ['given' => 'value'],
        ];

        $denormalized = new StubMessage($subject, $headers, $body);

        yield 'data is supported' => [
            'denormalizer' => new CompositeDenormalizer([
                new StubDenormalizer(true, $denormalized),
            ]),
            'data' => $data,
            'expected' => $denormalized,
        ];

        yield 'data not supported' => [
            'denormalizer' => new CompositeDenormalizer([
                new StubDenormalizer(false, $denormalized),
            ]),
            'data' => $data,
            'expected' => new DenormalizerNotFoundException(),
        ];
    }
}
