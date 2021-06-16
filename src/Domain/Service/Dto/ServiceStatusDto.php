<?php

namespace App\Domain\Service\Dto;

/**
 * @psalm-suppress MissingConstructor
 */
class ServiceStatusDto
{
    public string $name;
    public string $icon;
    public string $color;
    public bool $default = false;
}
