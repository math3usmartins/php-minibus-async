<?php

declare(strict_types=1);

namespace MiniBus\Transport\Handler;

use MiniBus\Envelope;
use MiniBus\Handler;
use MiniBus\Transport\Sender;
use MiniBus\Transport\Sender\SenderStamp;

final class TransportHandler implements Handler
{
    public function __construct(private Sender $sender) {}

    public function handle(Envelope $envelope): Envelope
    {
        $senderStamp = new SenderStamp();

        if ($envelope->stamps()->contains($senderStamp)) {
            return $envelope;
        }

        return $this->sender->send($envelope)->withStamp($senderStamp);
    }
}
