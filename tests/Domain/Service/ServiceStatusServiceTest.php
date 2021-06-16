<?php

namespace App\Tests\Domain\Service;

use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Repository\ServiceStatusRepository;
use App\Domain\Service\ServiceStatusService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ServiceStatusServiceTest extends TestCase
{
    public function testCreate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedStatus = new ServiceStatus('name', 'icon', 'color');
        $manager->expects(self::once())->method('persist')->with($expectedStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $repository->method('count')->willReturn(1);

        $service = new ServiceStatusService($manager, $repository);
        $status = $service->create('name', 'icon', 'color');
        self::assertEquals($expectedStatus, $status);
    }

    public function testCreateAsFirst(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedStatus = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $repository->method('count')->willReturn(0);

        $service = new ServiceStatusService($manager, $repository);
        $status = $service->create('name', 'icon', 'color');
        self::assertEquals($expectedStatus, $status);
    }

    public function testCreateAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedStatus = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $defaultStatus = new ServiceStatus('default', 'icon', 'color', true);
        $repository->method('findDefault')->willReturn($defaultStatus);

        $service = new ServiceStatusService($manager, $repository);
        $status = $service->create('name', 'icon', 'color', true);
        self::assertEquals($expectedStatus, $status);
        self::assertFalse($defaultStatus->isDefault());
    }

    public function testCreateAsDefaultAndNoDefaultAvailable(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedStatus = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $repository->method('findDefault')->willReturn(null);

        $service = new ServiceStatusService($manager, $repository);
        $status = $service->create('name', 'icon', 'color', true);
        self::assertEquals($expectedStatus, $status);
    }

    public function testUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);

        $service = new ServiceStatusService($manager, $repository);
        $oldStatus = new ServiceStatus('old name', 'old icon', 'old color');
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

        $repository = $this->createMock(ServiceStatusRepository::class);
        $defaultStatus = new ServiceStatus('default', 'icon', 'color', true);
        $repository->method('findDefault')->willReturn($defaultStatus);

        $service = new ServiceStatusService($manager, $repository);
        $oldStatus = new ServiceStatus('old name', 'old icon', 'old color');
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

        $repository = $this->createMock(ServiceStatusRepository::class);
        $firstStatus = new ServiceStatus('first', 'icon', 'color');
        $repository->method('findFirst')->willReturn($firstStatus);

        $service = new ServiceStatusService($manager, $repository);
        $oldStatus = new ServiceStatus('old name', 'old icon', 'old color', true);
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
        $status = new ServiceStatus('name', 'icon', 'color');
        $manager->expects(self::once())->method('remove')->with($status);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);

        $service = new ServiceStatusService($manager, $repository);
        $service->delete($status);
    }

    public function testDeleteAsDefaultAndNoEntriesAvailable(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $status = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('remove')->with($status);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $repository->method('findFirst')->willReturn(null);

        $service = new ServiceStatusService($manager, $repository);
        $service->delete($status);
    }

    public function testDeleteAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $status = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('remove')->with($status);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $firstStatus = new ServiceStatus('first', 'icon', 'color');
        $repository->method('findFirst')->willReturn($firstStatus);

        $service = new ServiceStatusService($manager, $repository);
        $service->delete($status);
        self::assertTrue($firstStatus->isDefault());
    }
}
