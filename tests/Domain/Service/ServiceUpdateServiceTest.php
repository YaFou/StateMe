<?php

namespace App\Tests\Domain\Service;

use App\Domain\Incident\Entity\IncidentUpdate;
use App\Domain\Service\Entity\Service;
use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Entity\ServiceUpdate;
use App\Domain\Service\ServiceUpdateService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ServiceUpdateServiceTest extends TestCase
{
    public function testUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $oldStatus = clone $newStatus = $this->createMock(ServiceStatus::class);
        $oldUpdate = new ServiceUpdate(
            $this->createMock(IncidentUpdate::class),
            $this->createMock(Service::class),
            $oldStatus
        );

        $service = new ServiceUpdateService($manager);
        $newUpdate = $service->update($oldUpdate, $newStatus);
        self::assertSame($oldUpdate, $newUpdate);
        self::assertSame($newStatus, $newUpdate->getStatus());
    }

    public function testCreate(): void
    {
        $expectedUpdate = new ServiceUpdate(
            $incident = $this->createMock(IncidentUpdate::class),
            $service = $this->createMock(Service::class),
            $status = $this->createMock(ServiceStatus::class)
        );

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('persist')->with($expectedUpdate);
        $manager->expects(self::once())->method('flush');

        $serviceUpdateService = new ServiceUpdateService($manager);
        $update = $serviceUpdateService->create($incident, $service, $status);
        self::assertEquals($expectedUpdate, $update);
    }

    public function testDelete(): void
    {
        $update = new ServiceUpdate(
            $this->createMock(IncidentUpdate::class),
            $this->createMock(Service::class),
            $this->createMock(ServiceStatus::class)
        );

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('remove')->with($update);
        $manager->expects(self::once())->method('flush');

        $serviceUpdateService = new ServiceUpdateService($manager);
        $serviceUpdateService->delete($update);
    }
}
