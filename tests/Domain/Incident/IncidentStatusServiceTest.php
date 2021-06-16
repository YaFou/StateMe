<?php

namespace App\Tests\Domain\Incident;

use App\Domain\Incident\Dto\IncidentStatusDto;
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

        $data = new IncidentStatusDto();
        $data->name = 'name';
        $data->icon = 'icon';
        $data->color = 'color';

        $service = new IncidentStatusService($manager, $repository);
        $status = $service->create($data);
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

        $data = new IncidentStatusDto();
        $data->name = 'name';
        $data->icon = 'icon';
        $data->color = 'color';

        $service = new IncidentStatusService($manager, $repository);
        $status = $service->create($data);
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

        $data = new IncidentStatusDto();
        $data->name = 'name';
        $data->icon = 'icon';
        $data->color = 'color';
        $data->default = true;

        $service = new IncidentStatusService($manager, $repository);
        $status = $service->create($data);
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

        $data = new IncidentStatusDto();
        $data->name = 'name';
        $data->icon = 'icon';
        $data->color = 'color';
        $data->default = true;

        $service = new IncidentStatusService($manager, $repository);
        $status = $service->create($data);
        self::assertEquals($expectedStatus, $status);
    }

    public function testUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $repository = $this->createMock(IncidentStatusRepository::class);

        $data = new IncidentStatusDto();
        $data->name = 'new name';
        $data->icon = 'new icon';
        $data->color = 'new color';

        $service = new IncidentStatusService($manager, $repository);
        $oldStatus = new IncidentStatus('old name', 'old icon', 'old color');
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

        $repository = $this->createMock(IncidentStatusRepository::class);
        $defaultStatus = new IncidentStatus('default', 'icon', 'color', true);
        $repository->method('findDefault')->willReturn($defaultStatus);

        $data = new IncidentStatusDto();
        $data->name = 'new name';
        $data->icon = 'new icon';
        $data->color = 'new color';
        $data->default = true;

        $service = new IncidentStatusService($manager, $repository);
        $oldStatus = new IncidentStatus('old name', 'old icon', 'old color');
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

        $repository = $this->createMock(IncidentStatusRepository::class);
        $firstStatus = new IncidentStatus('first', 'icon', 'color');
        $repository->method('findFirst')->willReturn($firstStatus);

        $data = new IncidentStatusDto();
        $data->name = 'new name';
        $data->icon = 'new icon';
        $data->color = 'new color';

        $service = new IncidentStatusService($manager, $repository);
        $oldStatus = new IncidentStatus('old name', 'old icon', 'old color', true);
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
