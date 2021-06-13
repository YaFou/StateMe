<?php

namespace App\Tests\Domain\Incident\Repository;

use App\Domain\Incident\Repository\IncidentStatusRepository;
use App\Tests\FixturesTrait;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IncidentStatusRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    /**
     * @dataProvider provideIncidentStatusCount
     */
    public function testGetIncidentCount(string $fixtureName, int $expectedCount): void
    {
        $this->loadFixture($fixtureName);
        self::assertSame($expectedCount, $this->getRepository()->getIncidentStatusCount());
    }

    private function getRepository(): IncidentStatusRepository
    {
        return self::getContainer()->get(IncidentStatusRepository::class);
    }

    public function testFindDefaultIncidentStatusWithNoDefaultIncident(): void
    {
        $this->loadFixture('incident/status/1');
        self::assertNull($this->getRepository()->findDefaultIncidentStatus());
    }

    public function testFindDefaultIncidentStatus(): void
    {
        ['incident-status' => $incidentStatus] = $this->loadFixture('incident/status/default');
        self::assertSame($incidentStatus, $this->getRepository()->findDefaultIncidentStatus());
    }

    public function testFindFirstIncidentStatusWithNoStatus(): void
    {
        self::assertNull($this->getRepository()->findFirstIncidentStatus());
    }

    public function testFindFirstIncidentStatusWith(): void
    {
        ['incident-status' => $incidentStatus] = $this->loadFixture('incident/status/1');
        self::assertSame($incidentStatus, $this->getRepository()->findFirstIncidentStatus());
    }

    /**
     * @return Generator
     *
     * @psalm-return Generator<int, array{0: string, 1: 0|1|2}, mixed, void>
     */
    public function provideIncidentStatusCount(): Generator
    {
        yield ['incident/status/0', 0];
        yield ['incident/status/1', 1];
        yield ['incident/status/2', 2];
    }
}
