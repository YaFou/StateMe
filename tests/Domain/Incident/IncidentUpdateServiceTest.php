<?php

namespace App\Tests\Domain\Incident;

use App\Domain\Incident\Dto\CreateIncidentUpdateDto;
use App\Domain\Incident\Dto\UpdateIncidentUpdateDto;
use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\Entity\IncidentUpdate;
use App\Domain\Incident\IncidentUpdateService;
use App\Domain\Service\Dto\CreateServiceUpdateDto;
use App\Domain\Service\Entity\Service;
use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Entity\ServiceUpdate;
use App\Domain\Service\ServiceUpdateService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class IncidentUpdateServiceTest extends TestCase
{
    private static IncidentStatus $incidentStatus;

    public static function setUpBeforeClass(): void
    {
        self::$incidentStatus = new IncidentStatus('name', 'icon', 'color');
    }

    public function testCreate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedUpdate = new IncidentUpdate(
            $incident = new Incident(),
            'message',
            self::$incidentStatus,
            $updatedAt = new DateTimeImmutable()
        );
        $manager->expects(self::once())->method('persist')->with($expectedUpdate);
        $manager->expects(self::once())->method('flush');

        $serviceUpdateService = $this->createMock(ServiceUpdateService::class);

        $data = new CreateIncidentUpdateDto();
        $data->message = 'message';
        $data->status = self::$incidentStatus;
        $data->updatedAt = $updatedAt;

        $service = new IncidentUpdateService($manager, $serviceUpdateService);
        $update = $service->create($incident, $data);
        self::assertEquals($expectedUpdate, $update);
    }

    public function testUpdateWithServiceUpdates(): void
    {
        $service1 = clone $service2 = $this->createMock(Service::class);
        $incident = new Incident();

        $expectedUpdate = new IncidentUpdate(
            $incident,
            'message',
            self::$incidentStatus,
            $updatedAt = new DateTimeImmutable()
        );

        new ServiceUpdate(
            $this->createMock(IncidentUpdate::class),
            $service1,
            $serviceStatus1 = $this->createMock(ServiceStatus::class)
        );

        new ServiceUpdate(
            $this->createMock(IncidentUpdate::class),
            $service2,
            $serviceStatus2 = $this->createMock(ServiceStatus::class)
        );

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('persist')->with($expectedUpdate);
        $manager->expects(self::once())->method('flush');

        $serviceUpdateDto1 = new CreateServiceUpdateDto();
        $serviceUpdateDto1->service = $service1;
        $serviceUpdateDto1->status = $serviceStatus1;

        $serviceUpdateDto2 = new CreateServiceUpdateDto();
        $serviceUpdateDto2->service = $service2;
        $serviceUpdateDto2->status = $serviceStatus2;

        $serviceUpdateService = $this->createMock(ServiceUpdateService::class);
        $serviceUpdateService->expects(self::exactly(2))
            ->method('create')
            ->withConsecutive([$expectedUpdate, $serviceUpdateDto1], [$expectedUpdate, $serviceUpdateDto2]);

        $data = new CreateIncidentUpdateDto();
        $data->message = 'message';
        $data->status = self::$incidentStatus;
        $data->updatedAt = $updatedAt;
        $data->serviceUpdates = [[$service1, $serviceStatus1], [$service1, $serviceStatus2]];

        $service = new IncidentUpdateService($manager, $serviceUpdateService);
        $update = $service->create($incident, $data);
        self::assertEquals($expectedUpdate, $update);
    }

    public function testUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $serviceUpdateService = $this->createMock(ServiceUpdateService::class);

        $oldIncidentStatus = clone self::$incidentStatus;
        $service = new IncidentUpdateService($manager, $serviceUpdateService);

        $oldUpdate = new IncidentUpdate(
            new Incident(),
            'old message',
            $oldIncidentStatus,
            new DateTimeImmutable()
        );

        $data = new UpdateIncidentUpdateDto();
        $data->message = 'new message';
        $data->status = self::$incidentStatus;
        $data->updatedAt = $newUpdatedAt = new DateTimeImmutable();

        $newUpdate = $service->update($oldUpdate, $data);

        self::assertSame($oldUpdate, $newUpdate);
        self::assertSame('new message', $newUpdate->getMessage());
        self::assertSame(self::$incidentStatus, $newUpdate->getStatus());
        self::assertSame($newUpdatedAt, $newUpdate->getUpdatedAt());
    }

    public function testDelete(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $update = new IncidentUpdate(new Incident(), 'message', self::$incidentStatus, new DateTimeImmutable());
        $manager->expects(self::once())->method('remove')->with($update);
        $manager->expects(self::once())->method('flush');

        $serviceUpdateService = $this->createMock(ServiceUpdateService::class);

        $service = new IncidentUpdateService($manager, $serviceUpdateService);
        $service->delete($update);
    }
}
