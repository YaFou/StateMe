<?php

namespace App\Domain\Incident;

use App\Domain\Incident\Entity\Incident;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class IncidentService
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function createIncident(string $name, DateTimeImmutable $createdAt, ?string $description = null): Incident
    {
        $incident = new Incident($name, $createdAt, $description);
        $this->manager->persist($incident);
        $this->manager->flush();

        return $incident;
    }

    public function updateIncident(
        Incident $incident,
        string $name,
        DateTimeImmutable $createdAt,
        ?string $description = null
    ): Incident {
        $incident->setName($name)
            ->setCreatedAt($createdAt)
            ->setDescription($description);

        $this->manager->flush();

        return $incident;
    }

    public function deleteIncident(Incident $incident): void
    {
        $this->manager->remove($incident);
        $this->manager->flush();
    }
}
