<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Worker\StopStrategy;

use Closure;
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
     * @dataProvider provideDispatchingAlarmSignalCases
     */
    public function testDispatchingAlarmSignal(
        Closure $strategyFactory,
        mixed $expected,
    ): void {
        /** @var ProcessControlStopStrategy $strategy */
        $strategy = $strategyFactory();
        self::assertInstanceOf(ProcessControlStopStrategy::class, $strategy);
        self::assertFalse($strategy->shouldStop());

        pcntl_alarm(1);
        sleep(2);
        $strategy->iterate();

        self::assertEquals($expected, $strategy->shouldStop());
    }

    public function provideDispatchingAlarmSignalCases(): iterable
    {
        yield 'it must stop on given signal' => [
            'strategy factory' => static fn () => new ProcessControlStopStrategy([SIGALRM]),
            'expected' => true,
        ];

        yield 'it must NOT stop when signal is different' => [
            'strategy factory' => static fn () => new ProcessControlStopStrategy([SIGTERM]),
            'expected' => false,
        ];
    }
}
