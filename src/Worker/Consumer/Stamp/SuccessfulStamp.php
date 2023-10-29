<?php

namespace MiniBus\Transport\Worker\Consumer\Stamp;

use MiniBus\Envelope\Stamp;

final class SuccessfulStamp implements Stamp
{
    const NAME = 'transport:worker:consumer:successful';

    public function name()
    {
        return self::NAME;
    }

    public function isEqualTo(Stamp $anotherStamp)
    {
        return $anotherStamp instanceof self;
    }
}
