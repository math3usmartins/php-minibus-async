<?php

namespace MiniBus\Transport\Worker;

use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Transport\Receiver;

interface Consumer
{
    /**
     * @return EnvelopeCollection
     */
    public function consume(Receiver $receiver);
}
