<?php

namespace App\Domain\Incident\Repository;

use App\Domain\Incident\Entity\IncidentStatus;

class IncidentStatusRepository
{
    public function getIncidentStatusCount(): int
    {
    }

    public function findDefaultIncidentStatus(): ?IncidentStatus
    {
    }

    public function findFirstIncidentStatus(): ?IncidentStatus
    {
    }
}
