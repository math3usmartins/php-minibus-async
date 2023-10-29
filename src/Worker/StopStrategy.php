<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker;

interface StopStrategy
{
    public function iterate();

    public function shouldStop(): bool;

    public function stop();
}
