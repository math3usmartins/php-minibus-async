<?php

namespace MiniBus\Transport;

use MiniBus\Envelope;

interface Unserializer
{
    /**
     * @param string $rawMessage
     *
     * @return Envelope
     */
    public function execute($rawMessage);
}
