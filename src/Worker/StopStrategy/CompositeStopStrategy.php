<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\StopStrategy;

use MiniBus\Transport\Worker\StopStrategy;

final class CompositeStopStrategy implements StopStrategy
{
    private bool $shouldStop = false;

    /**
     * @param StopStrategy[] $strategies
     */
    public function __construct(private array $strategies) {}

    public function iterate(): void
    {
        foreach ($this->strategies as $strategy) {
            $strategy->iterate();
        }
    }

    public function stop(): void
    {
        $this->shouldStop = true;
    }

    public function shouldStop(): bool
    {
        if ($this->shouldStop) {
            return true;
        }

        foreach ($this->strategies as $strategy) {
            if ($strategy->shouldStop()) {
                return true;
            }
        }

        return false;
    }
}
