<?php

namespace App\Domain\Service;

use App\Domain\Service\Dto\ServiceStatusDto;
use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Repository\ServiceStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class ServiceStatusService
{
    public function __construct(private EntityManagerInterface $manager, private ServiceStatusRepository $repository)
    {
    }

    public function create(ServiceStatusDto $data): ServiceStatus
    {
        $serviceStatus = new ServiceStatus($data->name, $data->icon, $data->color);

        if ($data->default) {
            $this->setAsDefault($serviceStatus);
        } elseif (!$this->repository->count()) {
            $serviceStatus->setDefault(true);
        }

        $this->manager->persist($serviceStatus);
        $this->manager->flush();

        return $serviceStatus;
    }

    private function setAsDefault(ServiceStatus $serviceStatus): void
    {
        $serviceStatus->setDefault(true);

        if (null !== $defaultServiceStatus = $this->repository->findDefault()) {
            $defaultServiceStatus->setDefault(false);
        }
    }

    public function update(ServiceStatus $serviceStatus, ServiceStatusDto $data): ServiceStatus
    {
        if ($serviceStatus->isDefault() !== $data->default) {
            if ($data->default) {
                $this->setAsDefault($serviceStatus);
            } elseif (null !== $firstServiceStatus = $this->repository->findFirst()) {
                $firstServiceStatus->setDefault(true);
            }
        }

        $serviceStatus->setName($data->name)
            ->setIcon($data->icon)
            ->setColor($data->color)
            ->setDefault($data->default);

        $this->manager->flush();

        return $serviceStatus;
    }

    public function delete(ServiceStatus $serviceStatus): void
    {
        if (
            $serviceStatus->isDefault() &&
            null !== $firstServiceStatus = $this->repository->findFirst()
        ) {
            $firstServiceStatus->setDefault(true);
        }

        $this->manager->remove($serviceStatus);
        $this->manager->flush();
    }
}
