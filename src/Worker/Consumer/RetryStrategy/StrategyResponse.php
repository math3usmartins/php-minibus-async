<?php

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

    /**
     * @return EnvelopeCollection
     */
    public function findRetriable()
    {
        return $this->envelopes->filter(
            function (Envelope $envelope) {
                return null !== $envelope->stamps()->last(RetriableStamp::NAME);
            }
        );
    }

    /**
     * @return EnvelopeCollection
     */
    public function findNotRetriable()
    {
        return $this->envelopes->filter(
            function (Envelope $envelope) {
                return null === $envelope->stamps()->last(RetriableStamp::NAME);
            }
        );
    }
}
