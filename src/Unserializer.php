<?php

declare(strict_types=1);

namespace MiniBus\Transport;

use MiniBus\Envelope;

interface Unserializer
{
    public function execute(string $rawMessage): Envelope;
}
