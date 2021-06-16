<?php

namespace App\Domain\Service\Dto;

/**
 * @psalm-suppress MissingConstructor
 */
class ServiceDto
{
    public string $name;
    public ?string $url = null;
}
