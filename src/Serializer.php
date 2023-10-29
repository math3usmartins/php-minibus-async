<?php

namespace MiniBus\Transport;

use MiniBus\Message;

interface Serializer
{
    /**
     * @return string
     */
    public function execute(Message $message);
}
