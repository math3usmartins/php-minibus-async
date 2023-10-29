<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\Consumer\Stamp;

use MiniBus\Envelope\Stamp;

final class SuccessfulStamp implements Stamp
{
    const NAME = 'transport:worker:consumer:successful';

    public function name(): string
    {
        return self::NAME;
    }

    public function isEqualTo(Stamp $anotherStamp): bool
    {
        return $anotherStamp instanceof self;
    }
}
