<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Unserializer\Denormalizer;

use Exception;
use MiniBus\Message;
use MiniBus\Transport\Unserializer\Denormalizer;

final class StubDenormalizer implements Denormalizer
{
    public function __construct(
        private bool $supports,
        private ?Message $result = null,
    ) {}

    public function supports(array $data): bool
    {
        return $this->supports;
    }

    public function execute(array $data): Message
    {
        if (!$this->result) {
            throw new Exception('Unexpected call to denormalize data');
        }

        return $this->result;
    }
}
