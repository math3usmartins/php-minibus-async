<?php

declare(strict_types=1);

namespace MiniBus\Transport;

use MiniBus\Message;

interface Serializer
{
    public function execute(Message $message): string;
}
