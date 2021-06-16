<?php

namespace App\Domain\Incident;

use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\Entity\IncidentUpdate;
use App\Domain\Service\ServiceUpdateService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class IncidentUpdateService
{
    public function __construct(
        private EntityManagerInterface $manager,
        private ServiceUpdateService $serviceUpdateService
    ) {
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

    /**
     * @param Incident $incident
     * @param string $message
     * @param IncidentStatus $status
     * @param DateTimeImmutable $updatedAt
     * @param array<array{Service, ServiceStatus}> $serviceUpdates
     * @return IncidentUpdate
     */
    public function updateIncident(
        Incident $incident,
        string $message,
        IncidentStatus $status,
        DateTimeImmutable $updatedAt,
        array $serviceUpdates = []
    ): IncidentUpdate {
        $update = new IncidentUpdate($incident, $message, $status, $updatedAt);
        $this->manager->persist($update);

        foreach ($serviceUpdates as $serviceUpdate) {
            $this->serviceUpdateService->create($update, $serviceUpdate[0], $serviceUpdate[1]);
        }

        $this->manager->flush();

        return $update;
    }

    public function deleteUpdate(IncidentUpdate $update): void
    {
        $this->manager->remove($update);
        $this->manager->flush();
    }
}
