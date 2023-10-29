<?php

namespace MiniBus\Transport\Sender;

use MiniBus\Envelope\Stamp;

final class SenderStamp implements Stamp
{
    const NAME = 'transport:sender';

    public function name()
    {
        return self::NAME;
    }

    public function isEqualTo(Stamp $anotherStamp)
    {
        return $anotherStamp instanceof self;
    }
}
