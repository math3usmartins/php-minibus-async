<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker;

use MiniBus\Transport\Receiver;

final class PrioritizedWorker extends AbstractWorker
{
    /**
     * @param Receiver[] $receivers
     */
    public function __construct(
        private StopStrategy $stopStrategy,
        private int $sleepTimeInSeconds,
        private Consumer $consumer,
        private array $receivers,
    ) {}

    public function stopStrategy(): StopStrategy
    {
        return $this->stopStrategy;
    }

    protected function receive(): void
    {
        // make sure to start with the first one
        reset($this->receivers);

        foreach ($this->receivers as $receiver) {
            if ($this->stopStrategy->shouldStop()) {
                return;
            }

            $envelopes = $this->consumer->consume($receiver);

            $this->stopStrategy->iterate();

            if (!$envelopes->isEmpty()) {
                // break loop to make sure receivers with higher priority are
                // processed first.
                return;
            }
        }

        sleep($this->sleepTimeInSeconds);
    }
}
