<?php

namespace MiniBus\Test\Transport\Serializer;

use MiniBus\Envelope;
use MiniBus\Transport\Serializer\Normalizer;

final class StubNormalizer implements Normalizer
{
    /**
     * @var array
     */
    private $normalized;

    public function __construct(array $normalized)
    {
        $this->normalized = $normalized;
    }

    public function normalize(Envelope $envelope)
    {
        return $this->normalized;
    }
}
