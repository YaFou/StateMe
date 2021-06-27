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

    /**
     * @psalm-var array<array{0: \App\Domain\Service\Entity\Service, 1: \App\Domain\Service\Entity\ServiceStatus}>
     */
    public array $serviceUpdates = [];
}
