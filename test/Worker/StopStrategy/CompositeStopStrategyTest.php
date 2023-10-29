<?php

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
     * @dataProvider scenarios
     *
     * @param mixed $expected
     */
    public function testWithOneIteration(
        Closure $compositeStrategyFactory,
        Closure $doSomething,
        $expected
    ) {
        /** @var CompositeStopStrategy $compositeStrategy */
        $compositeStrategy = $compositeStrategyFactory();
        static::assertInstanceOf(CompositeStopStrategy::class, $compositeStrategy);
        static::assertFalse($compositeStrategy->shouldStop());

        $doSomething();
        $compositeStrategy->iterate();

        static::assertEquals($expected, $compositeStrategy->shouldStop());
    }

    public function scenarios()
    {
        yield 'single strategy expected to stop after 1 iteration' => [
            'strategy factory' => function () {
                return new CompositeStopStrategy([
                    new MaxIterationCountStopStrategy(1),
                ]);
            },
            'do something' => function () {},
            'expected' => true,
        ];

        yield 'single strategy expected to stop after 2 iteration' => [
            'strategy factory' => function () {
                return new CompositeStopStrategy([
                    new MaxIterationCountStopStrategy(2),
                ]);
            },
            'do something' => function () {},
            'expected' => false,
        ];

        yield 'single strategy expected to stop on alarm signal' => [
            'strategy factory' => function () {
                return new CompositeStopStrategy([
                    new ProcessControlStopStrategy([SIGALRM]),
                ]);
            },
            'do something' => function () {
                pcntl_alarm(1);
                sleep(2);
            },
            'expected' => true,
        ];

        yield 'single strategy expected to stop on term signal' => [
            'strategy factory' => function () {
                return new CompositeStopStrategy([
                    new ProcessControlStopStrategy([SIGTERM]),
                ]);
            },
            'do something' => function () {
                pcntl_alarm(1);
                sleep(2);
            },
            'expected' => false,
        ];

        yield 'multiple strategies expected to stop on alarm signal' => [
            'strategy factory' => function () {
                return new CompositeStopStrategy([
                    new MaxIterationCountStopStrategy(10),
                    new ProcessControlStopStrategy([SIGALRM]),
                ]);
            },
            'do something' => function () {
                pcntl_alarm(1);
                sleep(2);
            },
            'expected' => true,
        ];

        $composite = new CompositeStopStrategy([
            new MaxIterationCountStopStrategy(10),
        ]);

        yield 'forced to stop despite anything else' => [
            'strategy factory' => function () use ($composite) {
                return $composite;
            },
            'do something' => function () use ($composite) {
                // force stop
                $composite->stop();
            },
            'expected' => true,
        ];
    }
}
