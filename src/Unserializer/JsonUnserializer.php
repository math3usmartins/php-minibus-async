<?php

declare(strict_types=1);

namespace MiniBus\Transport\Unserializer;

use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeFactory;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Transport\Unserializer;

final class JsonUnserializer implements Unserializer
{
    /**
     * @var Denormalizer
     */
    private $denormalizer;

    /**
     * @var EnvelopeFactory
     */
    private $envelopeFactory;

    public function __construct(
        Denormalizer $denormalizer,
        EnvelopeFactory $envelopeFactory
    ) {
        $this->denormalizer = $denormalizer;
        $this->envelopeFactory = $envelopeFactory;
    }

    public function execute(string $rawMessage): Envelope
    {
        return $this->envelopeFactory->create(
            $this->denormalizer->execute(
                json_decode($rawMessage, true)
            ),
            new StampCollection([])
        );
    }
}
