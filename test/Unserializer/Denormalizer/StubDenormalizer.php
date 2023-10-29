<?php

namespace MiniBus\Test\Transport\Unserializer\Denormalizer;

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
     * @param bool       $supports
     * @param mixed|null $result
     */
    public function __construct(
        $supports,
        $result = null
    ) {
        $this->supports = $supports;
        $this->result = $result;
    }

    public function supports(array $data)
    {
        return $this->supports;
    }

    public function execute(array $data)
    {
        return $this->result;
    }
}
