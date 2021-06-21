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

    /**
     * @psalm-var array<array{0: \App\Domain\Service\Entity\Service, 1: \App\Domain\Service\Entity\ServiceStatus}>
     */
    public array $serviceUpdates = [];
}
