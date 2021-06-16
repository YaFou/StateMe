<?php

namespace App\Domain\Incident\Dto;

/**
 * @psalm-suppress MissingConstructor
 */
class IncidentStatusDto
{
    public string $name;
    public string $icon;
    public string $color;
    public bool $default = false;
}
