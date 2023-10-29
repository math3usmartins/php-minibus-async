<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\Consumer\Stamp;

use Exception;
use MiniBus\Envelope\Stamp;
use MiniBus\Transport\Receiver;

final class FailedStamp implements Stamp
{
    public const NAME = 'transport:worker:consumer:failed';

    public function __construct(private Receiver $receiver, private Exception $exception) {}

    public function name(): string
    {
        return self::NAME;
    }

    public function receiver(): Receiver
    {
        return $this->receiver;
    }

    public function exception(): Exception
    {
        return $this->exception;
    }

    public function isEqualTo(Stamp $anotherStamp): bool
    {
        return $anotherStamp instanceof self
            && $anotherStamp->exception === $this->exception;
    }
}
