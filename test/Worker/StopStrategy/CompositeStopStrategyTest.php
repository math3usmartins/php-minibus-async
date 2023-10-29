<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Worker\StopStrategy;

use Closure;
use MiniBus\Transport\Worker\StopStrategy\CompositeStopStrategy;
use MiniBus\Transport\Worker\StopStrategy\MaxIterationCountStopStrategy;
use MiniBus\Transport\Worker\StopStrategy\ProcessControlStopStrategy;
use PHPUnit\Framework\TestCase;

use const SIGALRM;
use const SIGTERM;

/**
 * @covers \MiniBus\Transport\Worker\StopStrategy\CompositeStopStrategy
 *
 * @internal
 */
final class CompositeStopStrategyTest extends TestCase
{
    /**
     * @dataProvider provideWithOneIterationCases
     */
    public function testWithOneIteration(
        Closure $compositeStrategyFactory,
        Closure $doSomething,
        mixed $expected,
    ): void {
        /** @var CompositeStopStrategy $compositeStrategy */
        $compositeStrategy = $compositeStrategyFactory();
        self::assertInstanceOf(CompositeStopStrategy::class, $compositeStrategy);
        self::assertFalse($compositeStrategy->shouldStop());

        $doSomething();
        $compositeStrategy->iterate();

        self::assertEquals($expected, $compositeStrategy->shouldStop());
    }

    public function provideWithOneIterationCases(): iterable
    {
        yield 'single strategy expected to stop after 1 iteration' => [
            'strategy factory' => static fn () => new CompositeStopStrategy([
                new MaxIterationCountStopStrategy(1),
            ]),
            'do something' => static function (): void {},
            'expected' => true,
        ];

        yield 'single strategy expected to stop after 2 iteration' => [
            'strategy factory' => static fn () => new CompositeStopStrategy([
                new MaxIterationCountStopStrategy(2),
            ]),
            'do something' => static function (): void {},
            'expected' => false,
        ];

        yield 'single strategy expected to stop on alarm signal' => [
            'strategy factory' => static fn () => new CompositeStopStrategy([
                new ProcessControlStopStrategy([SIGALRM]),
            ]),
            'do something' => static function (): void {
                pcntl_alarm(1);
                sleep(2);
            },
            'expected' => true,
        ];

        yield 'single strategy expected to stop on term signal' => [
            'strategy factory' => static fn () => new CompositeStopStrategy([
                new ProcessControlStopStrategy([SIGTERM]),
            ]),
            'do something' => static function (): void {
                pcntl_alarm(1);
                sleep(2);
            },
            'expected' => false,
        ];

        yield 'multiple strategies expected to stop on alarm signal' => [
            'strategy factory' => static fn () => new CompositeStopStrategy([
                new MaxIterationCountStopStrategy(10),
                new ProcessControlStopStrategy([SIGALRM]),
            ]),
            'do something' => static function (): void {
                pcntl_alarm(1);
                sleep(2);
            },
            'expected' => true,
        ];

        $composite = new CompositeStopStrategy([
            new MaxIterationCountStopStrategy(10),
        ]);

        yield 'forced to stop despite anything else' => [
            'strategy factory' => static fn () => $composite,
            'do something' => static function () use ($composite): void {
                // force stop
                $composite->stop();
            },
            'expected' => true,
        ];
    }
}
