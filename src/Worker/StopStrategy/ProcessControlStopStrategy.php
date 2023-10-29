<?php

namespace MiniBus\Transport\Worker\StopStrategy;

use MiniBus\Transport\Worker\StopStrategy;
use function pcntl_signal;

final class ProcessControlStopStrategy implements StopStrategy
{
    private $shouldStop = false;

    public function __construct(array $signals)
    {
        foreach ($signals as $signal) {
            pcntl_signal($signal, function () {
                $this->stop();
            });
        }
    }

    public function iterate()
    {
        pcntl_signal_dispatch();
    }

    public function shouldStop()
    {
        return $this->shouldStop;
    }

    public function stop()
    {
        $this->shouldStop = true;
    }
}
