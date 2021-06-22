<?php

namespace App\Domain\Service\Dto;

use App\Domain\Service\Entity\ServiceStatus;

/**
 * @psalm-suppress MissingConstructor
 */
class ServiceStatusDto
{
    public string $name;
    public string $icon;
    public string $color;
    public bool $default = false;

    public static function fromServiceStatus(ServiceStatus $status): self
    {
        $dto = new self();
        $dto->name = $status->getName();
        $dto->icon = $status->getIcon();
        $dto->color = $status->getColor();

        return $dto;
    }
}
