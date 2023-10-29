<?php

declare(strict_types=1);

namespace MiniBus\Transport\Serializer;

use MiniBus\Message;
use MiniBus\Transport\Serializer;

final class JsonSerializer implements Serializer
{
    public function execute(Message $message): string
    {
        return json_encode(
            $message->normalize()
        );
    }
}
