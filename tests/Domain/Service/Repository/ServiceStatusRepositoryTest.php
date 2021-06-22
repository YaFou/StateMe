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
    public function testCount(?string $fixtureName, int $expectedCount): void
    {
        if (null !== $fixtureName) {
            $this->loadFixture($fixtureName);
        }

        self::assertSame($expectedCount, $this->getRepository()->count());
    }

    private function getRepository(): ServiceStatusRepository
    {
        return self::getContainer()->get(ServiceStatusRepository::class);
    }

    public function testFindDefaultWithNoDefault(): void
    {
        $this->loadFixture('service/status/one');
        self::assertNull($this->getRepository()->findDefault());
    }

    public function testFindDefault(): void
    {
        ['service-status' => $status] = $this->loadFixture('service/status/default');
        self::assertSame($status, $this->getRepository()->findDefault());
    }

    public function testFindFirstWithNoEntries(): void
    {
        self::assertNull($this->getRepository()->findFirst());
    }

    public function testFindFirst(): void
    {
        ['service-status' => $status] = $this->loadFixture('service/status/one');
        self::assertSame($status, $this->getRepository()->findFirst());
    }

    public function testFindAll(): void
    {
        $statuses = array_values($this->loadFixture('service/status/two'));
        self::assertSame($statuses, $this->getRepository()->findAll());
    }

    /**
     * @return Generator
     *
     * @psalm-return Generator<int, array{0: ?string, 1: 0|1|2}, mixed, void>
     */
    public function provideServiceStatusCount(): Generator
    {
        yield [null, 0];
        yield ['service/status/one', 1];
        yield ['service/status/two', 2];
    }
}
