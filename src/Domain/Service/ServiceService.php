<?php

namespace App\Domain\Service;

use App\Domain\Service\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;

class ServiceService
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function createService(string $name, ?string $url = null): Service
    {
        $service = new Service($name, $url);
        $this->manager->persist($service);
        $this->manager->flush();

        return $service;
    }

    public function updateService(Service $service, string $name, ?string $url = null): Service
    {
        $service->setName($name)
            ->setUrl($url);

        $this->manager->flush();

        return $service;
    }

    public function removeService(Service $service): void
    {
        $this->manager->remove($service);
        $this->manager->flush();
    }
}
