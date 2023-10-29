<?php

namespace MiniBus\Transport\Unserializer;

use MiniBus\Message;

interface Denormalizer
{
    /**
     * @return bool
     */
    public function supports(array $data);

    /**
     * @return Message
     */
    public function execute(array $data);
}
