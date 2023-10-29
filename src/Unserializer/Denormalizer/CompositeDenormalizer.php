<?php

declare(strict_types=1);

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

    public function supports(array $data): bool
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
     */
    public function execute(array $data): Message
    {
        foreach ($this->denormalizers as $denormalizer) {
            if ($denormalizer->supports($data)) {
                return $denormalizer->execute($data);
            }
        }

        throw new DenormalizerNotFoundException();
    }
}
