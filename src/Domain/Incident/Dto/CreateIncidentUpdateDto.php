<?php

namespace App\Domain\Incident\Dto;

use App\Domain\Incident\Entity\IncidentStatus;
use DateTimeImmutable;

/**
 * @psalm-suppress MissingConstructor
 */
class CreateIncidentUpdateDto
{
    public string $message;
    public IncidentStatus $status;
    public DateTimeImmutable $updatedAt;
    public array $serviceUpdates = [];
}
