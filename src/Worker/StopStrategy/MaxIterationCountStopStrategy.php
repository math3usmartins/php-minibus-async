<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\StopStrategy;

use MiniBus\Transport\Worker\StopStrategy;

final class MaxIterationCountStopStrategy implements StopStrategy
{
    private int $count = 0;

    public function __construct(private int $max) {}

    public function shouldStop(): bool
    {
        return $this->count >= $this->max;
    }

    public function iterate(): void
    {
        ++$this->count;
    }

    public function stop(): void
    {
        $this->count = $this->max;
    }
}
