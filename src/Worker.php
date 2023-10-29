<?php

declare(strict_types=1);

namespace MiniBus\Transport;

use MiniBus\Transport\Worker\StopStrategy;

interface Worker
{
    public function run(): void;

    public function stopStrategy(): StopStrategy;
}
