<?php

namespace MiniBus\Transport\Serializer;

use MiniBus\Message;
use MiniBus\Transport\Serializer;

final class JsonSerializer implements Serializer
{
    public function execute(Message $message)
    {
        return json_encode(
            $message->normalize()
        );
    }
}
