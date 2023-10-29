<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Unserializer\Denormalizer;

use MiniBus\Message;
use MiniBus\Transport\Unserializer\Denormalizer;

final class StubDenormalizer implements Denormalizer
{
    /**
     * @var bool
     */
    private $supports;

    /**
     * @var mixed|null
     */
    private $result;

    /**
     * @param mixed|null $result
     */
    public function __construct(
        bool $supports,
        $result = null
    ) {
        $this->supports = $supports;
        $this->result = $result;
    }

    public function supports(array $data): bool
    {
        return $this->supports;
    }

    public function execute(array $data): Message
    {
        return $this->result;
    }
}
