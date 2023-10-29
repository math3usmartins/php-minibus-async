<?php

namespace MiniBus\Transport\Serializer;

use MiniBus\Envelope;

interface Normalizer
{
    /**
     * @return array
     */
    public function normalize(Envelope $envelope);
}
