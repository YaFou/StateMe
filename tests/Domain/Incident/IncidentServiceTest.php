<?php

namespace App\Tests\Domain\Incident;

use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\IncidentService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class IncidentServiceTest extends TestCase
{
    public function testCreateIncident(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedIncident = new Incident('name', new DateTimeImmutable(), 'description');
        $manager->expects(self::once())->method('persist')->with($expectedIncident);
        $manager->expects(self::once())->method('flush');

        $service = new IncidentService($manager);
        $incident = $service->createIncident('name', $expectedIncident->getCreatedAt(), 'description');
        self::assertEquals($expectedIncident, $incident);
    }

    public function testUpdateIncident(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $service = new IncidentService($manager);
        $oldIncident = new Incident('old name', new DateTimeImmutable(), 'old description');
        $newIncident = $service->updateIncident(
            $oldIncident,
            'new name',
            $newCreatedAt = new DateTimeImmutable(),
            'new description'
        );

        self::assertSame($oldIncident, $newIncident);
        self::assertSame('new name', $newIncident->getName());
        self::assertSame($newCreatedAt, $newIncident->getCreatedAt());
        self::assertSame('new description', $newIncident->getDescription());
    }

    public function testDeleteIncident(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $incident = new Incident('name', new DateTimeImmutable(), 'description');
        $manager->expects(self::once())->method('remove')->with($incident);
        $manager->expects(self::once())->method('flush');

        $service = new IncidentService($manager);
        $service->deleteIncident($incident);
    }
}
