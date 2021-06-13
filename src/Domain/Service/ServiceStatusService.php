<?php

namespace App\Domain\Service;

use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Repository\ServiceStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class ServiceStatusService
{
    public function __construct(private EntityManagerInterface $manager, private ServiceStatusRepository $repository)
    {
    }

    public function createServiceStatus(
        string $name,
        string $icon,
        string $color,
        bool $default = false
    ): ServiceStatus {
        $serviceStatus = new ServiceStatus($name, $icon, $color);

        if ($default) {
            $this->setServiceStatusAsDefault($serviceStatus);
        } elseif (!$this->repository->getServiceStatusCount()) {
            $serviceStatus->setDefault(true);
        }

        $this->manager->persist($serviceStatus);
        $this->manager->flush();

        return $serviceStatus;
    }

    private function setServiceStatusAsDefault(ServiceStatus $serviceStatus): void
    {
        $serviceStatus->setDefault(true);

        if (null !== $defaultServiceStatus = $this->repository->findDefaultServiceStatus()) {
            $defaultServiceStatus->setDefault(false);
        }
    }

    public function updateServiceStatus(
        ServiceStatus $serviceStatus,
        string $name,
        string $icon,
        string $color,
        bool $default = false
    ): ServiceStatus {
        if ($serviceStatus->isDefault() !== $default) {
            if ($default) {
                $this->setServiceStatusAsDefault($serviceStatus);
            } elseif (null !== $firstServiceStatus = $this->repository->findFirstServiceStatus()) {
                $firstServiceStatus->setDefault(true);
            }
        }

        $serviceStatus->setName($name)
            ->setIcon($icon)
            ->setColor($color)
            ->setDefault($default);

        $this->manager->flush();

        return $serviceStatus;
    }

    public function deleteServiceStatus(ServiceStatus $serviceStatus): void
    {
        if (
            $serviceStatus->isDefault() &&
            null !== $firstServiceStatus = $this->repository->findFirstServiceStatus()
        ) {
            $firstServiceStatus->setDefault(true);
        }

        $this->manager->remove($serviceStatus);
        $this->manager->flush();
    }
}
