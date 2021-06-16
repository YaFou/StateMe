<?php

namespace App\Domain\Service;

use App\Domain\Service\Dto\ServiceDto;
use App\Domain\Service\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;

class ServiceService
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function create(ServiceDto $data): Service
    {
        $service = new Service($data->name, $data->url);
        $this->manager->persist($service);
        $this->manager->flush();

        return $service;
    }

    public function update(Service $service, ServiceDto $data): Service
    {
        $service->setName($data->name)
            ->setUrl($data->url);

        $this->manager->flush();

        return $service;
    }

    public function delete(Service $service): void
    {
        $this->manager->remove($service);
        $this->manager->flush();
    }
}
