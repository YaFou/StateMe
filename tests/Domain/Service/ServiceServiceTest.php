<?php

namespace App\Tests\Domain\Service;

use App\Domain\Service\Entity\Service;
use App\Domain\Service\ServiceService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ServiceServiceTest extends TestCase
{
    public function testCreateService(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('persist')->with(new Service('name', 'url'));
        $manager->expects(self::once())->method('flush');

        $serviceService = new ServiceService($manager);
        $service = $serviceService->createService('name', 'url');
        self::assertEquals(new Service('name', 'url'), $service);
    }

    public function testUpdateService(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $serviceService = new ServiceService($manager);
        $oldService = new Service('old name', 'old url');
        $newService = $serviceService->updateService($oldService, 'new name', 'new url');
        self::assertSame($oldService, $newService);
        self::assertSame('new name', $newService->getName());
        self::assertSame('new url', $newService->getUrl());
    }

    public function testDeleteService(): void
    {
        $service = new Service('old name', 'old url');

        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('remove')->with($service);
        $manager->expects(self::once())->method('flush');

        $serviceService = new ServiceService($manager);
        $serviceService->removeService($service);
    }
}
