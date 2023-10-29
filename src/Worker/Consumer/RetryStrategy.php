<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\Consumer;

use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Transport\Worker\Consumer\RetryStrategy\StrategyResponse;

interface RetryStrategy
{
    public function check(EnvelopeCollection $envelopes): StrategyResponse;
}
