<?php

namespace MiniBus\Transport\Serializer;

use MiniBus\Envelope;
use MiniBus\Transport\Serializer;

final class JsonSerializer implements Serializer
{
    /**
     * @var Normalizer
     */
    private $normalizer;

    public function __construct(Normalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function serialize(Envelope $envelope)
    {
        return json_encode(
            $this->normalizer->normalize($envelope)
        );
    }
}
