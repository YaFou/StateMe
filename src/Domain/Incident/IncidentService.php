<?php

namespace App\Domain\Incident;

use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Repository\IncidentStatusRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use LogicException;

class IncidentService
{
    public function __construct(
        private EntityManagerInterface $manager,
        private IncidentStatusRepository $incidentStatusRepository,
        private IncidentUpdateService $incidentUpdateService
    ) {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function create(string $message, DateTimeImmutable $createdAt): Incident
    {
        if (null === $defaultStatus = $this->incidentStatusRepository->findDefault()) {
            throw new LogicException('No default incident status found');
        }

        $incident = new Incident();
        $this->manager->persist($incident);
        $this->incidentUpdateService->updateIncident($incident, $message, $defaultStatus, $createdAt);
        $this->manager->flush();

        return $incident;
    }

    public function delete(Incident $incident): void
    {
        $this->manager->remove($incident);
        $this->manager->flush();
    }
}
