<?php

namespace App\Tests\Domain\Service;

use App\Domain\Service\Dto\ServiceStatusDto;
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

        $data = new ServiceStatusDto();
        $data->name = 'name';
        $data->icon = 'icon';
        $data->color = 'color';

        $service = new ServiceStatusService($manager, $repository);
        $status = $service->create($data);
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

        $data = new ServiceStatusDto();
        $data->name = 'name';
        $data->icon = 'icon';
        $data->color = 'color';

        $service = new ServiceStatusService($manager, $repository);
        $status = $service->create($data);
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

        $data = new ServiceStatusDto();
        $data->name = 'name';
        $data->icon = 'icon';
        $data->color = 'color';
        $data->default = true;

        $service = new ServiceStatusService($manager, $repository);
        $status = $service->create($data);
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

        $data = new ServiceStatusDto();
        $data->name = 'name';
        $data->icon = 'icon';
        $data->color = 'color';
        $data->default = true;

        $service = new ServiceStatusService($manager, $repository);
        $status = $service->create($data);
        self::assertEquals($expectedStatus, $status);
    }

    public function testUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(ServiceStatusRepository::class);

        $data = new ServiceStatusDto();
        $data->name = 'new name';
        $data->icon = 'new icon';
        $data->color = 'new color';

        $service = new ServiceStatusService($manager, $repository);
        $oldStatus = new ServiceStatus('old name', 'old icon', 'old color');
        $newStatus = $service->update($oldStatus, $data);

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

        $data = new ServiceStatusDto();
        $data->name = 'new name';
        $data->icon = 'new icon';
        $data->color = 'new color';
        $data->default = true;

        $service = new ServiceStatusService($manager, $repository);
        $oldStatus = new ServiceStatus('old name', 'old icon', 'old color');
        $newStatus = $service->update($oldStatus, $data);

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

        $data = new ServiceStatusDto();
        $data->name = 'new name';
        $data->icon = 'new icon';
        $data->color = 'new color';

        $service = new ServiceStatusService($manager, $repository);
        $oldStatus = new ServiceStatus('old name', 'old icon', 'old color', true);
        $newStatus = $service->update($oldStatus, $data);

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
