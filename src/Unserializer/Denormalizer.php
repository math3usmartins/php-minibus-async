<?php

namespace MiniBus\Transport\Unserializer;

use MiniBus\Envelope;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerNotCompatible;

interface Denormalizer
{
    /**
     * @return bool
     */
    public function supports(array $data);

    /**
     * @throws DenormalizerNotCompatible
     *
     * @return Envelope
     */
    public function denormalize(array $data);
}
