<?php

namespace App\Domain\Incident\Dto;

use App\Domain\Incident\Entity\IncidentStatus;
use DateTimeImmutable;

/**
 * @psalm-suppress MissingConstructor
 */
class UpdateIncidentUpdateDto
{
    public string $message;
    public IncidentStatus $status;
    public DateTimeImmutable $updatedAt;
}
