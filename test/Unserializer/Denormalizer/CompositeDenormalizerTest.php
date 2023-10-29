<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Unserializer\Denormalizer;

use Exception;
use Generator;
use MiniBus\Test\StubMessage;
use MiniBus\Transport\Unserializer\Denormalizer\CompositeDenormalizer;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerNotFoundException;
use PHPUnit\Framework\TestCase;
use function get_class;

/**
 * @covers \MiniBus\Transport\Unserializer\Denormalizer\CompositeDenormalizer
 *
 * @internal
 */
final class CompositeDenormalizerTest extends TestCase
{
    /**
     * @dataProvider supportsMethodScenarios
     *
     * @param mixed $expected
     */
    public function testSupportsMethod(
        CompositeDenormalizer $denormalizer,
        array $data,
        $expected
    ) {
        static::assertEquals($expected, $denormalizer->supports($data));
    }

    public function supportsMethodScenarios(): Generator
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
     * @dataProvider executeMethodScenarios
     *
     * @param mixed $expected
     *
     * @throws DenormalizerNotFoundException
     */
    public function testExecuteMethod(
        CompositeDenormalizer $denormalizer,
        array $data,
        $expected
    ) {
        if ($expected instanceof Exception) {
            self::expectException(get_class($expected));
            $denormalizer->execute($data);
        } else {
            static::assertEquals($expected, $denormalizer->execute($data));
        }
    }

    public function executeMethodScenarios(): Generator
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
