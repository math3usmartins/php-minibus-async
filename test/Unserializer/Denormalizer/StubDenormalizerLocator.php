<?php

namespace MiniBus\Test\Transport\Unserializer\Denormalizer;

use MiniBus\Transport\Unserializer\Denormalizer;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerLocator;
use MiniBus\Transport\Unserializer\Denormalizer\DenormalizerNotFoundException;

final class StubDenormalizerLocator implements DenormalizerLocator
{
    /**
     * @var Denormalizer|null
     */
    private $denormalizer;

    public function __construct(
        Denormalizer $denormalizer = null
    ) {
        $this->denormalizer = $denormalizer;
    }

    public function execute(array $data)
    {
        if (!$this->denormalizer) {
            throw new DenormalizerNotFoundException();
        }

        return $this->denormalizer;
    }
}
