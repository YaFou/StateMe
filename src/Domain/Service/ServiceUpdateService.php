<?php

namespace App\Domain\Service;

use App\Domain\Incident\Entity\IncidentUpdate;
use App\Domain\Service\Entity\Service;
use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Entity\ServiceUpdate;
use Doctrine\ORM\EntityManagerInterface;

class ServiceUpdateService
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function update(ServiceUpdate $update, ServiceStatus $status): ServiceUpdate
    {
        $update->setStatus($status);
        $this->manager->flush();

        return $update;
    }

    public function create(IncidentUpdate $incident, Service $service, ServiceStatus $status): ServiceUpdate
    {
        $update = new ServiceUpdate($incident, $service, $status);
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
