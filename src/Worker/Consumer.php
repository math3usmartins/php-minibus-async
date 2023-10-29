<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker;

use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Transport\Receiver;

interface Consumer
{
    public function consume(Receiver $receiver): EnvelopeCollection;
}
