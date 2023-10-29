<?php

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

    /**
     * @param int $max
     */
    public function __construct($max)
    {
        $this->max = $max;
    }

    public function shouldStop()
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
