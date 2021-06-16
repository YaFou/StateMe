<?php

namespace App\Domain\Incident\Dto;

use DateTimeImmutable;

/**
 * @psalm-suppress MissingConstructor
 */
class CreateIncidentDto
{
    public string $message;
    public DateTimeImmutable $createdAt;
}
