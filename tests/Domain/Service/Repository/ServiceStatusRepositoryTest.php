<?php

namespace App\Tests\Domain\Service\Repository;

use App\Domain\Service\Repository\ServiceStatusRepository;
use App\Tests\FixturesTrait;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceStatusRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    /**
     * @dataProvider provideServiceStatusCount
     */
    public function testGetServiceStatusCount(string $fixtureName, int $expectedCount): void
    {
        $this->loadFixture($fixtureName);
        self::assertSame($expectedCount, $this->getRepository()->getServiceStatusCount());
    }

    private function getRepository(): ServiceStatusRepository
    {
        return self::getContainer()->get(ServiceStatusRepository::class);
    }

    public function testFindDefaultServiceStatusWithNoDefaultIncident(): void
    {
        $this->loadFixture('service/status/1');
        self::assertNull($this->getRepository()->findDefaultServiceStatus());
    }

    public function testFindDefaultServiceStatus(): void
    {
        ['service-status' => $incidentStatus] = $this->loadFixture('service/status/default');
        self::assertSame($incidentStatus, $this->getRepository()->findDefaultServiceStatus());
    }

    public function testFindFirstServiceStatusWithNoStatus(): void
    {
        self::assertNull($this->getRepository()->findFirstServiceStatus());
    }

    public function testFindFirstServiceStatusWith(): void
    {
        ['service-status' => $incidentStatus] = $this->loadFixture('service/status/1');
        self::assertSame($incidentStatus, $this->getRepository()->findFirstServiceStatus());
    }

    /**
     * @return Generator
     *
     * @psalm-return Generator<int, array{0: string, 1: 0|1|2}, mixed, void>
     */
    public function provideServiceStatusCount(): Generator
    {
        yield ['service/status/0', 0];
        yield ['service/status/1', 1];
        yield ['service/status/2', 2];
    }
}
