<?php

declare(strict_types=1);

namespace MiniBus\Transport\Unserializer\Denormalizer;

use MiniBus\Message;
use MiniBus\Transport\Unserializer\Denormalizer;

final class CompositeDenormalizer implements Denormalizer
{
    /**
     * @param Denormalizer[] $denormalizers
     */
    public function __construct(private array $denormalizers) {}

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
