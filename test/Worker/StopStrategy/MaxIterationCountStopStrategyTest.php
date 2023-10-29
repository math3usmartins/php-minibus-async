<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Worker\StopStrategy;

use MiniBus\Transport\Worker\StopStrategy\MaxIterationCountStopStrategy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Transport\Worker\StopStrategy\MaxIterationCountStopStrategy
 *
 * @internal
 */
final class MaxIterationCountStopStrategyTest extends TestCase
{
    public function testItMustStopAfterOneIteration()
    {
        $strategy = new MaxIterationCountStopStrategy(1);
        static::assertFalse($strategy->shouldStop());

        $strategy->iterate();
        static::assertTrue($strategy->shouldStop());
    }

    public function testItMustStopAfterTwoIterations()
    {
        $strategy = new MaxIterationCountStopStrategy(2);
        static::assertFalse($strategy->shouldStop());

        $strategy->iterate();
        static::assertFalse($strategy->shouldStop());

        $strategy->iterate();
        static::assertTrue($strategy->shouldStop());
    }
}
