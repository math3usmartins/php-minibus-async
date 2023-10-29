<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\Consumer\Stamp;

use MiniBus\Envelope\Stamp;

final class RetriableStamp implements Stamp
{
    public const NAME = 'transport:worker:consumer:retriable';

    public function name(): string
    {
        return self::NAME;
    }

    public function isEqualTo(Stamp $anotherStamp): bool
    {
        return $anotherStamp instanceof self;
    }
}
