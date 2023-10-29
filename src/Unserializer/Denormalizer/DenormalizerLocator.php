<?php

namespace MiniBus\Transport\Unserializer\Denormalizer;

use MiniBus\Transport\Unserializer\Denormalizer;

interface DenormalizerLocator
{
    /**
     * @return Denormalizer
     */
    public function execute(array $data);
}
