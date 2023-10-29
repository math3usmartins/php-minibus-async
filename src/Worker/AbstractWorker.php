<?php

namespace MiniBus\Transport\Worker;

use MiniBus\Transport\Worker;

abstract class AbstractWorker implements Worker
{
    public function run()
    {
        while (!$this->stopStrategy()->shouldStop()) {
            $this->receive();
        }
    }

    abstract protected function receive();
}
