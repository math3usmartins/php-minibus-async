<?php

declare(strict_types=1);

namespace MiniBus\Transport\Sender;

use MiniBus\Envelope\Stamp;

final class SenderStamp implements Stamp
{
    const NAME = 'transport:sender';

    public function name(): string
    {
        return self::NAME;
    }

    public function isEqualTo(Stamp $anotherStamp): bool
    {
        return $anotherStamp instanceof self;
    }
}
