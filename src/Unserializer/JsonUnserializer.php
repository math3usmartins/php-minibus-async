<?php

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

    /**
     * @param string $rawMessage
     *
     * @return Envelope
     */
    public function execute($rawMessage)
    {
        return $this->envelopeFactory->create(
            $this->denormalizer->execute(
                json_decode($rawMessage, true)
            ),
            new StampCollection([])
        );
    }
}
