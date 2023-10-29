<?php

namespace MiniBus\Transport;

use MiniBus\Envelope;

interface Sender
{
    /**
     * @return Envelope
     */
    public function send(Envelope $envelope);
}
