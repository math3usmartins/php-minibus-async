<?php

namespace MiniBus\Transport;

use MiniBus\Envelope;

interface Unserializer
{
    /**
     * @param string $rawEnvelope
     *
     * @return Envelope
     */
    public function unserialize($rawEnvelope);
}
