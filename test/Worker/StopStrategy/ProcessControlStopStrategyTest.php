<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Worker\StopStrategy;

use Closure;
use Generator;
use MiniBus\Transport\Worker\StopStrategy\ProcessControlStopStrategy;
use PHPUnit\Framework\TestCase;
use const SIGALRM;
use const SIGTERM;

/**
 * @covers \MiniBus\Transport\Worker\StopStrategy\ProcessControlStopStrategy
 *
 * @internal
 */
final class ProcessControlStopStrategyTest extends TestCase
{
    /**
     * @dataProvider scenariosDispatchingAlarmSignal
     *
     * @param mixed $expected
     */
    public function testDispatchingAlarmSignal(
        Closure $strategyFactory,
        $expected
    ) {
        /** @var ProcessControlStopStrategy $strategy */
        $strategy = $strategyFactory();
        static::assertInstanceOf(ProcessControlStopStrategy::class, $strategy);
        static::assertFalse($strategy->shouldStop());

        pcntl_alarm(1);
        sleep(2);
        $strategy->iterate();

        static::assertEquals($expected, $strategy->shouldStop());
    }

    public function scenariosDispatchingAlarmSignal(): Generator
    {
        yield 'it must stop on given signal' => [
            'strategy factory' => function () {
                return new ProcessControlStopStrategy([SIGALRM]);
            },
            'expected' => true,
        ];

        yield 'it must NOT stop when signal is different' => [
            'strategy factory' => function () {
                return new ProcessControlStopStrategy([SIGTERM]);
            },
            'expected' => false,
        ];
    }
}
