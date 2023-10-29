<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\Consumer\RetryStrategy;

use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Transport\Worker\Consumer\Stamp\RetriableStamp;

final class StrategyResponse
{
    public function __construct(private EnvelopeCollection $envelopes) {}

    public function findRetriable(): EnvelopeCollection
    {
        return $this->envelopes->filter(
            static fn (Envelope $envelope) => null !== $envelope->stamps()->last(RetriableStamp::NAME),
        );
    }

    public function findNotRetriable(): EnvelopeCollection
    {
        return $this->envelopes->filter(
            static fn (Envelope $envelope) => null === $envelope->stamps()->last(RetriableStamp::NAME),
        );
    }
}
