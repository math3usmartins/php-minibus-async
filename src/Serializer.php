<?php

namespace MiniBus\Transport;

use MiniBus\Envelope;

interface Serializer
{
    /**
     * @return string
     */
    public function serialize(Envelope $envelope);
}
