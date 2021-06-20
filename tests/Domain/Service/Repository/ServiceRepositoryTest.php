<?php

namespace App\Tests\Domain\Service\Repository;

use App\Domain\Service\Repository\ServiceRepository;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testFindAll(): void
    {
        $expectedResults = array_values($this->loadFixture('service/all'));
        $repository = self::getContainer()->get(ServiceRepository::class);
        self::assertSame($expectedResults, $repository->findAll());
    }
}
