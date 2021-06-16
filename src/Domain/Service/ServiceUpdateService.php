<?php

namespace App\Domain\Service;

use App\Domain\Incident\Entity\IncidentUpdate;
use App\Domain\Service\Dto\CreateServiceUpdateDto;
use App\Domain\Service\Dto\UpdateServiceUpdateDto;
use App\Domain\Service\Entity\ServiceUpdate;
use Doctrine\ORM\EntityManagerInterface;

class ServiceUpdateService
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function update(ServiceUpdate $update, UpdateServiceUpdateDto $data): ServiceUpdate
    {
        $update->setStatus($data->status);
        $this->manager->flush();

        return $update;
    }

    public function create(IncidentUpdate $incident, CreateServiceUpdateDto $data): ServiceUpdate
    {
        $update = new ServiceUpdate($incident, $data->service, $data->status);
        $this->manager->persist($update);
        $this->manager->flush();

        return $update;
    }

    public function delete(ServiceUpdate $update): void
    {
        $this->manager->remove($update);
        $this->manager->flush();
    }
}
