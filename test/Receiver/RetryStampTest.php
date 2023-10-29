<?php

namespace MiniBus\Test\Transport\Receiver;

use MiniBus\Envelope\Stamp;
use MiniBus\Test\Envelope\Stamp\StubStamp;
use MiniBus\Transport\Receiver\RetryStamp;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Transport\Receiver\RetryStamp
 */
final class RetryStampTest extends TestCase
{
    /**
     * @dataProvider scenarios
     *
     * @param mixed $expected
     */
    public function testIsEqualTo(
        RetryStamp $stamp,
        Stamp $anotherStamp,
        $expected
    ) {
        static::assertEquals($expected, $stamp->isEqualTo($anotherStamp));
    }

    public function scenarios()
    {
        return [
            'same stamp' => [
                'stamp' => new RetryStamp(1),
                'another stamp' => new RetryStamp(1),
                'expected same' => true,
            ],
            'different timestamp' => [
                'stamp' => new RetryStamp(1),
                'another stamp' => new RetryStamp(2),
                'expected same' => false,
            ],
            'different class name' => [
                'stamp' => new RetryStamp(1),
                'another stamp' => new StubStamp('foo', 'bar'),
                'expected same' => false,
            ],
        ];
    }
}
