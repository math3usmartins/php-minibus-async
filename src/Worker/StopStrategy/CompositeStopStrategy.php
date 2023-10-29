<?php

namespace MiniBus\Transport\Worker\StopStrategy;

use MiniBus\Transport\Worker\StopStrategy;

final class CompositeStopStrategy implements StopStrategy
{
    private $shouldStop = false;

    private $strategies;

    /**
     * @param StopStrategy[] $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function iterate()
    {
        foreach ($this->strategies as $strategy) {
            $strategy->iterate();
        }
    }

    public function stop()
    {
        $this->shouldStop = true;
    }

    public function shouldStop()
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
