<?php

namespace MiniBus\Test\Transport\Sender;

use MiniBus\Envelope;
use MiniBus\Transport\Sender;

final class SpySender implements Sender
{
    private $envelopes = [];

    public function send(Envelope $envelope)
    {
        $this->envelopes[] = $envelope;

        return $envelope;
    }

    public function envelopes()
    {
        return $this->envelopes;
    }
}
