<?php

namespace MiniBus\Test\Transport\Unserializer\Denormalizer;

use MiniBus\Envelope;
use MiniBus\Transport\Unserializer\Denormalizer;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerNotCompatible;

final class StubDenormalizer implements Denormalizer
{
    /**
     * @var mixed|null
     */
    private $envelope;

    public function __construct(
        Envelope $envelope = null
    ) {
        $this->envelope = $envelope;
    }

    public function supports(array $data)
    {
        return empty($this->envelope);
    }

    public function denormalize(array $data)
    {
        if (empty($this->envelope)) {
            throw new DenormalizerNotCompatible('Stub denormalizer not compatible with given data');
        }

        return $this->envelope;
    }
}
