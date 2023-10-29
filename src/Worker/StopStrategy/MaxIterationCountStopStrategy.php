<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\StopStrategy;

use MiniBus\Transport\Worker\StopStrategy;

final class MaxIterationCountStopStrategy implements StopStrategy
{
    /**
     * @var int
     */
    private $max;

    /**
     * @var int
     */
    private $count = 0;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function shouldStop(): bool
    {
        return $this->count >= $this->max;
    }

    public function iterate()
    {
        ++$this->count;
    }

    public function stop()
    {
        $this->count = $this->max;
    }
}
