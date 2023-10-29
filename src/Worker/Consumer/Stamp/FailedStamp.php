<?php

namespace MiniBus\Transport\Worker\Consumer\Stamp;

use Exception;
use MiniBus\Envelope\Stamp;
use MiniBus\Transport\Receiver;

final class FailedStamp implements Stamp
{
    const NAME = 'transport:worker:consumer:failed';

    /**
     * @var Receiver
     */
    private $receiver;

    /**
     * @var Exception
     */
    private $exception;

    public function __construct(Receiver $receiver, Exception $exception)
    {
        $this->exception = $exception;
        $this->receiver = $receiver;
    }

    public function name()
    {
        return self::NAME;
    }

    public function receiver()
    {
        return $this->receiver;
    }

    public function exception()
    {
        return $this->exception;
    }

    public function isEqualTo(Stamp $anotherStamp)
    {
        return $anotherStamp instanceof self
            && $anotherStamp->exception === $this->exception;
    }
}
