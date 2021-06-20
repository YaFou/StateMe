<?php

namespace App\Domain\Service\Dto;

use App\Domain\Service\Entity\Service;

/**
 * @psalm-suppress MissingConstructor
 */
class ServiceDto
{
    public string $name;
    public ?string $url = null;

    public static function fromService(Service $service): self
    {
        $dto = new self();
        $dto->name = $service->getName();
        $dto->url = $service->getUrl();

        return $dto;
    }
}
