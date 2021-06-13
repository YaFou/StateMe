<?php

namespace App\Tests\Domain\Incident\Entity;

use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Incident\Entity\IncidentUpdate;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class IncidentTest extends TestCase
{
    public function testGetLastUpdate(): void
    {
        $status = $this->createMock(IncidentStatus::class);
        $incident = new Incident();
        new IncidentUpdate($incident, 'message', $status, new DateTimeImmutable('-1 hour'));
        $expectedUpdate = new IncidentUpdate($incident, 'message', $status, new DateTimeImmutable('+1 hour'));
        new IncidentUpdate($incident, 'message', $status, new DateTimeImmutable());

        self::assertSame($expectedUpdate, $incident->getLastUpdate());
    }

    public function testGetLastUpdateWithNoUpdates(): void
    {
        $incident = new Incident();
        self::assertNull($incident->getLastUpdate());
    }
}
