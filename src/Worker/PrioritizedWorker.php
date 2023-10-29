<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker;

use MiniBus\Transport\Receiver;

final class PrioritizedWorker extends AbstractWorker
{
    /**
     * @var StopStrategy
     */
    private $stopStrategy;

    /**
     * @var int
     */
    private $sleepTimeInSeconds;

    /**
     * @var Receiver[]
     */
    private $receivers;

    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @param Receiver[] $receivers
     */
    public function __construct(
        StopStrategy $stopStrategy,
        int $sleepTimeInSeconds,
        Consumer $consumer,
        array $receivers
    ) {
        $this->stopStrategy = $stopStrategy;
        $this->sleepTimeInSeconds = $sleepTimeInSeconds;
        $this->consumer = $consumer;
        $this->receivers = $receivers;
    }

    public function stopStrategy(): StopStrategy
    {
        return $this->stopStrategy;
    }

    protected function receive()
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
