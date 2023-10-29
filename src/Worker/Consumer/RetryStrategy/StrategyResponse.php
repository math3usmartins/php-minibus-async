<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\Consumer\RetryStrategy;

use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Transport\Worker\Consumer\Stamp\RetriableStamp;

final class StrategyResponse
{
    /**
     * @var EnvelopeCollection
     */
    private $envelopes;

    public function __construct(EnvelopeCollection $envelopes)
    {
        $this->envelopes = $envelopes;
    }

    public function findRetriable(): EnvelopeCollection
    {
        return $this->envelopes->filter(
            function (Envelope $envelope) {
                return null !== $envelope->stamps()->last(RetriableStamp::NAME);
            }
        );
    }

    public function findNotRetriable(): EnvelopeCollection
    {
        return $this->envelopes->filter(
            function (Envelope $envelope) {
                return null === $envelope->stamps()->last(RetriableStamp::NAME);
            }
        );
    }
}
