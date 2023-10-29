<?php

namespace MiniBus\Transport\Receiver;

use MiniBus\Envelope\Stamp;

final class RetryStamp implements Stamp
{
    const NAME = 'transport:receiver:retry';

    /**
     * @var int
     */
    private $retryAt;

    /**
     * @param int $retryAt
     */
    public function __construct($retryAt)
    {
        $this->retryAt = $retryAt;
    }

    public function name()
    {
        return self::NAME;
    }

    public function isEqualTo(Stamp $anotherStamp)
    {
        return ($anotherStamp instanceof self)
            && ($anotherStamp->name() === self::NAME)
            && ($anotherStamp->retryAt === $this->retryAt);
    }
}
