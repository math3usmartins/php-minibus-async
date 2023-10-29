<?php

namespace MiniBus\Transport\Worker;

interface StopStrategy
{
    public function iterate();

    /**
     * @return bool
     */
    public function shouldStop();

    public function stop();
}
