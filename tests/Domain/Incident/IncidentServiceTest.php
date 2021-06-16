<?php

namespace App\Tests\Domain\Incident;

use App\Domain\Incident\Dto\CreateIncidentDto;
use App\Domain\Incident\Dto\CreateIncidentUpdateDto;
use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\IncidentService;
use App\Domain\Incident\IncidentUpdateService;
use App\Domain\Incident\Repository\IncidentStatusRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use PHPUnit\Framework\TestCase;

class IncidentServiceTest extends TestCase
{
    private static IncidentStatus $incidentStatus;

    public static function setUpBeforeClass(): void
    {
        self::$incidentStatus = new IncidentStatus('name', 'icon', 'color');
    }

    public function testCreate(): void
    {
        $expectedIncident = new Incident();
        $createdAt = new DateTimeImmutable();

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('persist')->with($expectedIncident);
        $manager->expects(self::once())->method('flush');

        $incidentStatusRepository = $this->createMock(IncidentStatusRepository::class);
        $incidentStatusRepository->method('findDefault')->willReturn(self::$incidentStatus);

        $updateData = new CreateIncidentUpdateDto();
        $updateData->message = 'message';
        $updateData->status = self::$incidentStatus;
        $updateData->updatedAt = $createdAt;

        $incidentUpdateService = $this->createMock(IncidentUpdateService::class);
        $incidentUpdateService->expects(self::once())
            ->method('create')
            ->with($expectedIncident, $updateData);

        $data = new CreateIncidentDto();
        $data->message = 'message';
        $data->createdAt = $createdAt;

        $service = new IncidentService($manager, $incidentStatusRepository, $incidentUpdateService);
        $incident = $service->create($data);
        self::assertEquals($expectedIncident, $incident);
    }

    public function testCreateWithNoDefaultAvailable(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);

        $incidentStatusRepository = $this->createMock(IncidentStatusRepository::class);
        $incidentStatusRepository->method('findDefault')->willReturn(null);

        $service = new IncidentService(
            $manager,
            $incidentStatusRepository,
            $this->createMock(IncidentUpdateService::class)
        );

        $data = new CreateIncidentDto();
        $data->message = 'message';
        $data->createdAt = new DateTimeImmutable();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('No default incident status found');
        $service->create($data);
    }

    public function testDelete(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $incident = new Incident();
        $manager->expects(self::once())->method('remove')->with($incident);
        $manager->expects(self::once())->method('flush');

        $service = new IncidentService(
            $manager,
            $this->createMock(IncidentStatusRepository::class),
            $this->createMock(IncidentUpdateService::class)
        );

        $service->delete($incident);
    }
}
