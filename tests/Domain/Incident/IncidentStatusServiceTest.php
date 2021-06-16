<?php

namespace App\Tests\Domain\Incident;

use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\IncidentStatusService;
use App\Domain\Incident\Repository\IncidentStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class IncidentStatusServiceTest extends TestCase
{
    public function testCreate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedStatus = new IncidentStatus('name', 'icon', 'color');
        $manager->expects(self::once())->method('persist')->with($expectedStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $repository->method('count')->willReturn(1);

        $service = new IncidentStatusService($manager, $repository);
        $status = $service->create('name', 'icon', 'color');
        self::assertEquals($expectedStatus, $status);
    }

    public function testCreateAsFirst(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedStatus = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $repository->method('count')->willReturn(0);

        $service = new IncidentStatusService($manager, $repository);
        $status = $service->create('name', 'icon', 'color');
        self::assertEquals($expectedStatus, $status);
    }

    public function testCreateAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedStatus = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $defaultStatus = new IncidentStatus('default', 'icon', 'color', true);
        $repository->method('findDefault')->willReturn($defaultStatus);

        $service = new IncidentStatusService($manager, $repository);
        $status = $service->create('name', 'icon', 'color', true);
        self::assertEquals($expectedStatus, $status);
        self::assertFalse($defaultStatus->isDefault());
    }

    public function testCreateAsDefaultWithNoDefaultAvailable(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedStatus = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $repository->method('findDefault')->willReturn(null);

        $service = new IncidentStatusService($manager, $repository);
        $status = $service->create('name', 'icon', 'color', true);
        self::assertEquals($expectedStatus, $status);
    }

    public function testUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);

        $service = new IncidentStatusService($manager, $repository);
        $oldStatus = new IncidentStatus('old name', 'old icon', 'old color');
        $newStatus = $service->update(
            $oldStatus,
            'new name',
            'new icon',
            'new color'
        );

        self::assertSame($oldStatus, $newStatus);
        self::assertSame('new name', $newStatus->getName());
        self::assertSame('new icon', $newStatus->getIcon());
        self::assertSame('new color', $newStatus->getColor());
    }

    public function testUpdateAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $defaultStatus = new IncidentStatus('default', 'icon', 'color', true);
        $repository->method('findDefault')->willReturn($defaultStatus);

        $service = new IncidentStatusService($manager, $repository);
        $oldStatus = new IncidentStatus('old name', 'old icon', 'old color');
        $newStatus = $service->update(
            $oldStatus,
            'new name',
            'new icon',
            'new color',
            true
        );

        self::assertSame($oldStatus, $newStatus);
        self::assertSame('new name', $newStatus->getName());
        self::assertSame('new icon', $newStatus->getIcon());
        self::assertSame('new color', $newStatus->getColor());
        self::assertFalse($defaultStatus->isDefault());
    }

    public function testUpdateAsNotDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $firstStatus = new IncidentStatus('first', 'icon', 'color');
        $repository->method('findFirst')->willReturn($firstStatus);

        $service = new IncidentStatusService($manager, $repository);
        $oldStatus = new IncidentStatus('old name', 'old icon', 'old color', true);
        $newStatus = $service->update(
            $oldStatus,
            'new name',
            'new icon',
            'new color'
        );

        self::assertSame($oldStatus, $newStatus);
        self::assertSame('new name', $newStatus->getName());
        self::assertSame('new icon', $newStatus->getIcon());
        self::assertSame('new color', $newStatus->getColor());
        self::assertFalse($newStatus->isDefault());
        self::assertTrue($firstStatus->isDefault());
    }

    public function testDelete(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $status = new IncidentStatus('name', 'icon', 'color');
        $manager->expects(self::once())->method('remove')->with($status);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);

        $service = new IncidentStatusService($manager, $repository);
        $service->delete($status);
    }

    public function testDeleteDefaultWithNoMoreAvailable(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $status = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('remove')->with($status);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $repository->method('findFirst')->willReturn(null);

        $service = new IncidentStatusService($manager, $repository);
        $service->delete($status);
    }

    public function testDeleteAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $status = new IncidentStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('remove')->with($status);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);
        $firstStatus = new IncidentStatus('first', 'icon', 'color');
        $repository->method('findFirst')->willReturn($firstStatus);

        $service = new IncidentStatusService($manager, $repository);
        $service->delete($status);
        self::assertTrue($firstStatus->isDefault());
    }
}
