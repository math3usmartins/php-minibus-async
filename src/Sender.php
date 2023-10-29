<?php

declare(strict_types=1);

namespace MiniBus\Transport;

use MiniBus\Envelope;

interface Sender
{
    public function send(Envelope $envelope): Envelope;
}
