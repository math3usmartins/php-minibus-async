<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\StopStrategy;

use MiniBus\Transport\Worker\StopStrategy;

use function pcntl_signal;

final class ProcessControlStopStrategy implements StopStrategy
{
    private bool $shouldStop = false;

    public function __construct(array $signals)
    {
        foreach ($signals as $signal) {
            pcntl_signal($signal, function (): void {
                $this->stop();
            });
        }
    }

    public function iterate(): void
    {
        pcntl_signal_dispatch();
    }

    public function shouldStop(): bool
    {
        return $this->shouldStop;
    }

    public function stop(): void
    {
        $this->shouldStop = true;
    }
}
