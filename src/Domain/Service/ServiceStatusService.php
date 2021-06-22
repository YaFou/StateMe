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
        $status = new ServiceStatus($data->name, $data->icon, $data->color);

        if ($data->default) {
            $this->setAsDefault($status);
        } elseif (!$this->repository->count()) {
            $status->setDefault(true);
        }

        $this->manager->persist($status);
        $this->manager->flush();

        return $status;
    }

    private function setAsDefault(ServiceStatus $status): void
    {
        $status->setDefault(true);

        if (null !== $defaultStatus = $this->repository->findDefault()) {
            $defaultStatus->setDefault(false);
        }
    }

    public function update(ServiceStatus $status, ServiceStatusDto $data): ServiceStatus
    {
        if ($status->isDefault() !== $data->default) {
            if ($data->default) {
                $this->setAsDefault($status);
            } elseif (null !== $firstServiceStatus = $this->repository->findFirst()) {
                $firstServiceStatus->setDefault(true);
            }
        }

        $status->setName($data->name)
            ->setIcon($data->icon)
            ->setColor($data->color)
            ->setDefault($data->default);

        $this->manager->flush();

        return $status;
    }

    public function delete(ServiceStatus $status): void
    {
        if (
            $status->isDefault() &&
            null !== $firstServiceStatus = $this->repository->findFirst()
        ) {
            $firstServiceStatus->setDefault(true);
        }

        $this->manager->remove($status);
        $this->manager->flush();
    }
}
