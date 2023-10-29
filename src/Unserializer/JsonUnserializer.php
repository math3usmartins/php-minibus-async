<?php

namespace MiniBus\Transport\Unserializer;

use MiniBus\Envelope;
use MiniBus\Transport\Unserializer;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerLocator;

final class JsonUnserializer implements Unserializer
{
    /**
     * @var DenormalizerLocator
     */
    private $denormalizerLocator;

    public function __construct(
        DenormalizerLocator $denormalizerLocator
    ) {
        $this->denormalizerLocator = $denormalizerLocator;
    }

    /**
     * @param string $rawEnvelope
     *
     * @return Envelope
     */
    public function unserialize($rawEnvelope)
    {
        $data = json_decode($rawEnvelope, true);

        return $this->denormalizerLocator
            ->execute($data)
            ->denormalize($data);
    }
}
