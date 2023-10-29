<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker;

interface StopStrategy
{
    public function iterate(): void;

    public function shouldStop(): bool;

    public function stop(): void;
}
