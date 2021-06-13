<?php

namespace App\Tests\Domain\Incident;

use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\IncidentStatusService;
use App\Domain\Incident\Repository\IncidentStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class IncidentStatusServiceTest extends TestCase
{
    public function testCreateIncidentStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedIncidentStatus = new IncidentStatus('name', 'icon', 'color');
        $manager->expects(self::once())->method('persist')->with($expectedIncidentStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $repository->method('getIncidentStatusCount')->willReturn(1);

        $service = new IncidentStatusService($manager, $repository);
        $incidentStatus = $service->createIncidentStatus('name', 'icon', 'color');
        self::assertEquals($expectedIncidentStatus, $incidentStatus);
    }

    public function testCreateIncidentStatusAsFirstStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedIncidentStatus = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedIncidentStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $repository->method('getIncidentStatusCount')->willReturn(0);

        $service = new IncidentStatusService($manager, $repository);
        $incidentStatus = $service->createIncidentStatus('name', 'icon', 'color');
        self::assertEquals($expectedIncidentStatus, $incidentStatus);
    }

    public function testCreateIncidentStatusAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedIncidentStatus = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedIncidentStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $defaultIncidentStatus = new IncidentStatus('default', 'icon', 'color', true);
        $repository->method('findDefaultIncidentStatus')->willReturn($defaultIncidentStatus);

        $service = new IncidentStatusService($manager, $repository);
        $incidentStatus = $service->createIncidentStatus('name', 'icon', 'color', true);
        self::assertEquals($expectedIncidentStatus, $incidentStatus);
        self::assertFalse($defaultIncidentStatus->isDefault());
    }

    public function testCreateIncidentStatusAsDefaultAndNoDefaultStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedIncidentStatus = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedIncidentStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $repository->method('findDefaultIncidentStatus')->willReturn(null);

        $service = new IncidentStatusService($manager, $repository);
        $incidentStatus = $service->createIncidentStatus('name', 'icon', 'color', true);
        self::assertEquals($expectedIncidentStatus, $incidentStatus);
    }

    public function testUpdateIncidentStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);

        $service = new IncidentStatusService($manager, $repository);
        $oldIncidentStatus = new IncidentStatus('old name', 'old icon', 'old color');
        $newIncidentStatus = $service->updateIncidentStatus(
            $oldIncidentStatus,
            'new name',
            'new icon',
            'new color'
        );

        self::assertSame($oldIncidentStatus, $newIncidentStatus);
        self::assertSame('new name', $newIncidentStatus->getName());
        self::assertSame('new icon', $newIncidentStatus->getIcon());
        self::assertSame('new color', $newIncidentStatus->getColor());
    }

    public function testUpdateIncidentStatusAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $defaultIncidentStatus = new IncidentStatus('default', 'icon', 'color', true);
        $repository->method('findDefaultIncidentStatus')->willReturn($defaultIncidentStatus);

        $service = new IncidentStatusService($manager, $repository);
        $oldIncidentStatus = new IncidentStatus('old name', 'old icon', 'old color');
        $newIncidentStatus = $service->updateIncidentStatus(
            $oldIncidentStatus,
            'new name',
            'new icon',
            'new color',
            true
        );

        self::assertSame($oldIncidentStatus, $newIncidentStatus);
        self::assertSame('new name', $newIncidentStatus->getName());
        self::assertSame('new icon', $newIncidentStatus->getIcon());
        self::assertSame('new color', $newIncidentStatus->getColor());
        self::assertFalse($defaultIncidentStatus->isDefault());
    }

    public function testUpdateIncidentStatusAsNotDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $firstIncidentStatus = new IncidentStatus('first', 'icon', 'color');
        $repository->method('findFirstIncidentStatus')->willReturn($firstIncidentStatus);

        $service = new IncidentStatusService($manager, $repository);
        $oldIncidentStatus = new IncidentStatus('old name', 'old icon', 'old color', true);
        $newIncidentStatus = $service->updateIncidentStatus(
            $oldIncidentStatus,
            'new name',
            'new icon',
            'new color'
        );

        self::assertSame($oldIncidentStatus, $newIncidentStatus);
        self::assertSame('new name', $newIncidentStatus->getName());
        self::assertSame('new icon', $newIncidentStatus->getIcon());
        self::assertSame('new color', $newIncidentStatus->getColor());
        self::assertFalse($newIncidentStatus->isDefault());
        self::assertTrue($firstIncidentStatus->isDefault());
    }

    public function testDeleteIncidentStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $incidentStatus = new IncidentStatus('name', 'icon', 'color');
        $manager->expects(self::once())->method('remove')->with($incidentStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);

        $service = new IncidentStatusService($manager, $repository);
        $service->deleteIncidentStatus($incidentStatus);
    }

    public function testDeleteIncidentStatusAsDefaultAndNoMoreStatuses(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $incidentStatus = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('remove')->with($incidentStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $repository->method('findFirstIncidentStatus')->willReturn(null);

        $service = new IncidentStatusService($manager, $repository);
        $service->deleteIncidentStatus($incidentStatus);
    }

    public function testDeleteIncidentStatusAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $incidentStatus = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('remove')->with($incidentStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $firstIncidentStatus = new IncidentStatus('first', 'icon', 'color');
        $repository->method('findFirstIncidentStatus')->willReturn($firstIncidentStatus);

        $service = new IncidentStatusService($manager, $repository);
        $service->deleteIncidentStatus($incidentStatus);
        self::assertTrue($firstIncidentStatus->isDefault());
    }
}
