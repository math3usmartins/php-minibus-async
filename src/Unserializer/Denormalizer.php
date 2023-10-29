<?php

declare(strict_types=1);

namespace MiniBus\Transport\Unserializer;

use MiniBus\Message;

interface Denormalizer
{
    public function supports(array $data): bool;

    public function execute(array $data): Message;
}
