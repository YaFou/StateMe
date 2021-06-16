<?php

namespace App\Tests\Domain\Service;

use App\Domain\Service\Entity\Service;
use App\Domain\Service\ServiceService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ServiceServiceTest extends TestCase
{
    public function testCreate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('persist')->with(new Service('name', 'url'));
        $manager->expects(self::once())->method('flush');

        $serviceService = new ServiceService($manager);
        $service = $serviceService->create('name', 'url');
        self::assertEquals(new Service('name', 'url'), $service);
    }

    public function testUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $serviceService = new ServiceService($manager);
        $oldService = new Service('old name', 'old url');
        $newService = $serviceService->update($oldService, 'new name', 'new url');
        self::assertSame($oldService, $newService);
        self::assertSame('new name', $newService->getName());
        self::assertSame('new url', $newService->getUrl());
    }

    public function testDelete(): void
    {
        $service = new Service('old name', 'old url');

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('remove')->with($service);
        $manager->expects(self::once())->method('flush');

        $serviceService = new ServiceService($manager);
        $serviceService->delete($service);
    }
}
