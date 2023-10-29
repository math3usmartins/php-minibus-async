<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Sender;

use MiniBus\Envelope;
use MiniBus\Transport\Sender;

final class SpySender implements Sender
{
    /**
     * @var Envelope[]
     */
    private array $envelopes = [];

    public function send(Envelope $envelope): Envelope
    {
        $this->envelopes[] = $envelope;

        return $envelope;
    }

    /**
     * @return Envelope[]
     */
    public function envelopes(): array
    {
        return $this->envelopes;
    }
}
