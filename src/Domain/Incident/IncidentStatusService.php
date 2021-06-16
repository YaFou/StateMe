<?php

namespace App\Domain\Incident;

use App\Domain\Incident\Dto\IncidentStatusDto;
use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\Repository\IncidentStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class IncidentStatusService
{
    public function __construct(private EntityManagerInterface $manager, private IncidentStatusRepository $repository)
    {
    }

    public function create(IncidentStatusDto $data): IncidentStatus
    {
        $status = new IncidentStatus($data->name, $data->icon, $data->color);

        if ($data->default) {
            $this->setStatusAsDefault($status);
        } elseif (!$this->repository->count()) {
            $status->setDefault(true);
        }

        $this->manager->persist($status);
        $this->manager->flush();

        return $status;
    }

    private function setStatusAsDefault(IncidentStatus $status): void
    {
        $status->setDefault(true);

        if (null !== $defaultIncidentStatus = $this->repository->findDefault()) {
            $defaultIncidentStatus->setDefault(false);
        }
    }

    public function update(IncidentStatus $incidentStatus, IncidentStatusDto $data): IncidentStatus
    {
        if ($incidentStatus->isDefault() !== $data->default) {
            if ($data->default) {
                $this->setStatusAsDefault($incidentStatus);
            } elseif (null !== $firstIncidentStatus = $this->repository->findFirst()) {
                $firstIncidentStatus->setDefault(true);
            }
        }

        $incidentStatus->setName($data->name)
            ->setIcon($data->icon)
            ->setColor($data->color)
            ->setDefault($data->default);

        $this->manager->flush();

        return $incidentStatus;
    }

    public function delete(IncidentStatus $incidentStatus): void
    {
        if (
            $incidentStatus->isDefault() &&
            null !== $firstIncidentStatus = $this->repository->findFirst()
        ) {
            $firstIncidentStatus->setDefault(true);
        }

        $this->manager->remove($incidentStatus);
        $this->manager->flush();
    }
}
