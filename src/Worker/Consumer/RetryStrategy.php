<?php

namespace MiniBus\Transport\Worker\Consumer;

use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Transport\Worker\Consumer\RetryStrategy\StrategyResponse;

interface RetryStrategy
{
    /**
     * @return StrategyResponse
     */
    public function check(EnvelopeCollection $envelopes);
}
