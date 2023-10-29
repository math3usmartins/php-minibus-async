<?php

declare(strict_types=1);

namespace MiniBus\Transport\Unserializer;

use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeFactory;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Transport\Unserializer;

final class JsonUnserializer implements Unserializer
{
    public function __construct(
        private Denormalizer $denormalizer,
        private EnvelopeFactory $envelopeFactory,
    ) {}

    public function execute(string $rawMessage): Envelope
    {
        return $this->envelopeFactory->create(
            $this->denormalizer->execute(
                // @phpstan-ignore-next-line
                json_decode($rawMessage, true, 512, JSON_THROW_ON_ERROR),
            ),
            new StampCollection([]),
        );
    }
}
