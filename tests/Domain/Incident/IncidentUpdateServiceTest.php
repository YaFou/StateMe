<?php

namespace App\Tests\Domain\Incident;

use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\Entity\IncidentUpdate;
use App\Domain\Incident\IncidentUpdateService;
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

    public function testUpdateIncident(): void
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

        $service = new IncidentUpdateService($manager);
        $update = $service->updateIncident($incident, 'message', self::$incidentStatus, $updatedAt);
        self::assertEquals($expectedUpdate, $update);
    }

    public function testUpdateUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $manager->expects(self::once())->method('flush');

        $oldIncidentStatus = clone self::$incidentStatus;
        $service = new IncidentUpdateService($manager);

        $oldUpdate = new IncidentUpdate(
            new Incident(),
            'old message',
            $oldIncidentStatus,
            new DateTimeImmutable()
        );

        $newUpdate = $service->updateUpdate(
            $oldUpdate,
            'new message',
            self::$incidentStatus,
            $newUpdatedAt = new DateTimeImmutable()
        );

        self::assertSame($oldUpdate, $newUpdate);
        self::assertSame('new message', $newUpdate->getMessage());
        self::assertSame(self::$incidentStatus, $newUpdate->getStatus());
        self::assertSame($newUpdatedAt, $newUpdate->getUpdatedAt());
    }

    public function testDeleteUpdate(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $update = new IncidentUpdate(new Incident(), 'message', self::$incidentStatus, new DateTimeImmutable());
        $manager->expects(self::once())->method('remove')->with($update);
        $manager->expects(self::once())->method('flush');

        $service = new IncidentUpdateService($manager);
        $service->deleteUpdate($update);
    }
}
