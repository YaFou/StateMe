<?php

namespace App\Domain\Incident;

use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\Entity\IncidentUpdate;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class IncidentUpdateService
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function updateUpdate(
        IncidentUpdate $update,
        string $message,
        IncidentStatus $status,
        DateTimeImmutable $updatedAt
    ): IncidentUpdate {
        $update->setMessage($message)
            ->setStatus($status)
            ->setUpdatedAt($updatedAt);

        $this->manager->flush();

        return $update;
    }

    public function updateIncident(
        Incident $incident,
        string $message,
        IncidentStatus $status,
        DateTimeImmutable $updatedAt
    ): IncidentUpdate {
        $update = new IncidentUpdate($incident, $message, $status, $updatedAt);
        $this->manager->persist($update);
        $this->manager->flush();

        return $update;
    }

    public function deleteUpdate(IncidentUpdate $update): void
    {
        $this->manager->remove($update);
        $this->manager->flush();
    }
}
