<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Worker\Consumer\RetryStrategy;

use Closure;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Transport\Worker\Consumer\RetryStrategy;
use MiniBus\Transport\Worker\Consumer\RetryStrategy\StrategyResponse;

final class CallbackRetryStrategy implements RetryStrategy
{
    /**
     * @var Closure
     */
    private $callback;

    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function check(EnvelopeCollection $envelopes): StrategyResponse
    {
        return new StrategyResponse(
            $envelopes->map($this->callback)
        );
    }
}
