<?php

namespace App\Tests\Domain\Service;

use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Repository\ServiceStatusRepository;
use App\Domain\Service\ServiceStatusService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ServiceStatusServiceTest extends TestCase
{
    public function testCreateServiceStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedServiceStatus = new ServiceStatus('name', 'icon', 'color');
        $manager->expects(self::once())->method('persist')->with($expectedServiceStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $repository->method('getServiceStatusCount')->willReturn(1);

        $service = new ServiceStatusService($manager, $repository);
        $serviceStatus = $service->createServiceStatus('name', 'icon', 'color');
        self::assertEquals($expectedServiceStatus, $serviceStatus);
    }

    public function testCreateServiceStatusAsFirstStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedServiceStatus = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedServiceStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $repository->method('getServiceStatusCount')->willReturn(0);

        $service = new ServiceStatusService($manager, $repository);
        $serviceStatus = $service->createServiceStatus('name', 'icon', 'color');
        self::assertEquals($expectedServiceStatus, $serviceStatus);
    }

    public function testCreateServiceStatusAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedServiceStatus = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedServiceStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $defaultServiceStatus = new ServiceStatus('default', 'icon', 'color', true);
        $repository->method('findDefaultServiceStatus')->willReturn($defaultServiceStatus);

        $service = new ServiceStatusService($manager, $repository);
        $serviceStatus = $service->createServiceStatus('name', 'icon', 'color', true);
        self::assertEquals($expectedServiceStatus, $serviceStatus);
        self::assertFalse($defaultServiceStatus->isDefault());
    }

    public function testCreateServiceStatusAsDefaultAndNoDefaultStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $expectedServiceStatus = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('persist')->with($expectedServiceStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $repository->method('findDefaultServiceStatus')->willReturn(null);

        $service = new ServiceStatusService($manager, $repository);
        $serviceStatus = $service->createServiceStatus('name', 'icon', 'color', true);
        self::assertEquals($expectedServiceStatus, $serviceStatus);
    }

    public function testUpdateServiceStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);

        $service = new ServiceStatusService($manager, $repository);
        $oldServiceStatus = new ServiceStatus('old name', 'old icon', 'old color');
        $newServiceStatus = $service->updateServiceStatus(
            $oldServiceStatus,
            'new name',
            'new icon',
            'new color'
        );

        self::assertSame($oldServiceStatus, $newServiceStatus);
        self::assertSame('new name', $newServiceStatus->getName());
        self::assertSame('new icon', $newServiceStatus->getIcon());
        self::assertSame('new color', $newServiceStatus->getColor());
    }

    public function testUpdateServiceStatusAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $defaultServiceStatus = new ServiceStatus('default', 'icon', 'color', true);
        $repository->method('findDefaultServiceStatus')->willReturn($defaultServiceStatus);

        $service = new ServiceStatusService($manager, $repository);
        $oldServiceStatus = new ServiceStatus('old name', 'old icon', 'old color');
        $newServiceStatus = $service->updateServiceStatus(
            $oldServiceStatus,
            'new name',
            'new icon',
            'new color',
            true
        );

        self::assertSame($oldServiceStatus, $newServiceStatus);
        self::assertSame('new name', $newServiceStatus->getName());
        self::assertSame('new icon', $newServiceStatus->getIcon());
        self::assertSame('new color', $newServiceStatus->getColor());
        self::assertFalse($defaultServiceStatus->isDefault());
    }

    public function testUpdateServiceStatusAsNotDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $firstServiceStatus = new ServiceStatus('first', 'icon', 'color');
        $repository->method('findFirstServiceStatus')->willReturn($firstServiceStatus);

        $service = new ServiceStatusService($manager, $repository);
        $oldServiceStatus = new ServiceStatus('old name', 'old icon', 'old color', true);
        $newServiceStatus = $service->updateServiceStatus(
            $oldServiceStatus,
            'new name',
            'new icon',
            'new color'
        );

        self::assertSame($oldServiceStatus, $newServiceStatus);
        self::assertSame('new name', $newServiceStatus->getName());
        self::assertSame('new icon', $newServiceStatus->getIcon());
        self::assertSame('new color', $newServiceStatus->getColor());
        self::assertFalse($newServiceStatus->isDefault());
        self::assertTrue($firstServiceStatus->isDefault());
    }

    public function testDeleteServiceStatus(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $serviceStatus = new ServiceStatus('name', 'icon', 'color');
        $manager->expects(self::once())->method('remove')->with($serviceStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);

        $service = new ServiceStatusService($manager, $repository);
        $service->deleteServiceStatus($serviceStatus);
    }

    public function testDeleteServiceStatusAsDefaultAndNoMoreStatuses(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $serviceStatus = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('remove')->with($serviceStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $repository->method('findFirstServiceStatus')->willReturn(null);

        $service = new ServiceStatusService($manager, $repository);
        $service->deleteServiceStatus($serviceStatus);
    }

    public function testDeleteServiceStatusAsDefault(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $serviceStatus = new ServiceStatus('name', 'icon', 'color', true);
        $manager->expects(self::once())->method('remove')->with($serviceStatus);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);
        $firstServiceStatus = new ServiceStatus('first', 'icon', 'color');
        $repository->method('findFirstServiceStatus')->willReturn($firstServiceStatus);

        $service = new ServiceStatusService($manager, $repository);
        $service->deleteServiceStatus($serviceStatus);
        self::assertTrue($firstServiceStatus->isDefault());
    }
}
