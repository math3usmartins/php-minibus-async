<?php

namespace MiniBus\Transport\Unserializer\Denormalizer;

use MiniBus\Message;
use MiniBus\Transport\Unserializer\Denormalizer;

final class CompositeDenormalizer implements Denormalizer
{
    /**
     * @var Denormalizer[]
     */
    private $denormalizers;

    /**
     * @param Denormalizer[] $normalizers
     */
    public function __construct(array $normalizers)
    {
        $this->denormalizers = $normalizers;
    }

    /**
     * @return bool
     */
    public function supports(array $data)
    {
        foreach ($this->denormalizers as $denormalizer) {
            if ($denormalizer->supports($data)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws DenormalizerNotFoundException
     *
     * @return Message
     */
    public function execute(array $data)
    {
        foreach ($this->denormalizers as $denormalizer) {
            if ($denormalizer->supports($data)) {
                return $denormalizer->execute($data);
            }
        }

        throw new DenormalizerNotFoundException();
    }
}
