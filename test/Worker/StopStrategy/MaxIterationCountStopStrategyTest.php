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
    public function testItMustStopAfterOneIteration(): void
    {
        $strategy = new MaxIterationCountStopStrategy(1);
        self::assertFalse($strategy->shouldStop());

        $strategy->iterate();
        self::assertTrue($strategy->shouldStop());
    }

    public function testItMustStopAfterTwoIterations(): void
    {
        $strategy = new MaxIterationCountStopStrategy(2);
        self::assertFalse($strategy->shouldStop());

        $strategy->iterate();
        self::assertFalse($strategy->shouldStop());

        $strategy->iterate();
        self::assertTrue($strategy->shouldStop());
    }
}
