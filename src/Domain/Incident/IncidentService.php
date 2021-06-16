<?php

namespace App\Domain\Incident;

use App\Domain\Incident\Dto\CreateIncidentDto;
use App\Domain\Incident\Dto\CreateIncidentUpdateDto;
use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Repository\IncidentStatusRepository;
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
    public function create(CreateIncidentDto $data): Incident
    {
        if (null === $defaultStatus = $this->incidentStatusRepository->findDefault()) {
            throw new LogicException('No default incident status found');
        }

        $incident = new Incident();
        $this->manager->persist($incident);

        $updateData = new CreateIncidentUpdateDto();
        $updateData->message = $data->message;
        $updateData->status = $defaultStatus;
        $updateData->updatedAt = $data->createdAt;
        $this->incidentUpdateService->create($incident, $updateData);

        $this->manager->flush();

        return $incident;
    }

    public function delete(Incident $incident): void
    {
        $this->manager->remove($incident);
        $this->manager->flush();
    }
}
