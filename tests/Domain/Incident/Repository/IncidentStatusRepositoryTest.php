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
    public function testCount(?string $fixtureName, int $expectedCount): void
    {
        if (null !== $fixtureName) {
            $this->loadFixture($fixtureName);
        }

        self::assertSame($expectedCount, $this->getRepository()->count());
    }

    private function getRepository(): IncidentStatusRepository
    {
        return self::getContainer()->get(IncidentStatusRepository::class);
    }

    public function testFindDefaultWithNoDefault(): void
    {
        $this->loadFixture('incident/status/one');
        self::assertNull($this->getRepository()->findDefault());
    }

    public function testFindDefault(): void
    {
        ['incident-status' => $status] = $this->loadFixture('incident/status/default');
        self::assertSame($status, $this->getRepository()->findDefault());
    }

    public function testFindFirstWithNoEntries(): void
    {
        self::assertNull($this->getRepository()->findFirst());
    }

    public function testFindFirst(): void
    {
        ['incident-status' => $status] = $this->loadFixture('incident/status/one');
        self::assertSame($status, $this->getRepository()->findFirst());
    }

    /**
     * @return Generator
     *
     * @psalm-return Generator<int, array{0: ?string, 1: 0|1|2}, mixed, void>
     */
    public function provideIncidentStatusCount(): Generator
    {
        yield [null, 0];
        yield ['incident/status/one', 1];
        yield ['incident/status/two', 2];
    }
}
