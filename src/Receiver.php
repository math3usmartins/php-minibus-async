<?php

declare(strict_types=1);

namespace MiniBus\Transport;

use MiniBus\Envelope\EnvelopeCollection;

interface Receiver
{
    public function fetch(): EnvelopeCollection;

    public function ack(EnvelopeCollection $envelopes): void;

    public function reject(EnvelopeCollection $envelopes): void;

    public function retry(EnvelopeCollection $envelopes): void;
}
