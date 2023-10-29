<?php

namespace MiniBus\Transport;

use MiniBus\Transport\Worker\StopStrategy;

interface Worker
{
    public function run();

    /**
     * @return StopStrategy
     */
    public function stopStrategy();
}
