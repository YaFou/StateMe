<?php

namespace App\Domain\Incident;

use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\Repository\IncidentStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class IncidentStatusService
{
    public function __construct(private EntityManagerInterface $manager, private IncidentStatusRepository $repository)
    {
    }

    public function createIncidentStatus(
        string $name,
        string $icon,
        string $color,
        bool $default = false
    ): IncidentStatus {
        $incidentStatus = new IncidentStatus($name, $icon, $color);

        if ($default) {
            $this->setIncidentStatusAsDefault($incidentStatus);
        } elseif (!$this->repository->getIncidentStatusCount()) {
            $incidentStatus->setDefault(true);
        }

        $this->manager->persist($incidentStatus);
        $this->manager->flush();

        return $incidentStatus;
    }

    private function setIncidentStatusAsDefault(IncidentStatus $incidentStatus): void
    {
        $incidentStatus->setDefault(true);

        if (null !== $defaultIncidentStatus = $this->repository->findDefaultIncidentStatus()) {
            $defaultIncidentStatus->setDefault(false);
        }
    }

    public function updateIncidentStatus(
        IncidentStatus $incidentStatus,
        string $name,
        string $icon,
        string $color,
        bool $default = false
    ): IncidentStatus {
        if ($incidentStatus->isDefault() !== $default) {
            if ($default) {
                $this->setIncidentStatusAsDefault($incidentStatus);
            } elseif (null !== $firstIncidentStatus = $this->repository->findFirstIncidentStatus()) {
                $firstIncidentStatus->setDefault(true);
            }
        }

        $incidentStatus->setName($name)
            ->setIcon($icon)
            ->setColor($color)
            ->setDefault($default);

        $this->manager->flush();

        return $incidentStatus;
    }

    public function deleteIncidentStatus(IncidentStatus $incidentStatus): void
    {
        if (
            $incidentStatus->isDefault() &&
            null !== $firstIncidentStatus = $this->repository->findFirstIncidentStatus()
        ) {
            $firstIncidentStatus->setDefault(true);
        }

        $this->manager->remove($incidentStatus);
        $this->manager->flush();
    }
}
