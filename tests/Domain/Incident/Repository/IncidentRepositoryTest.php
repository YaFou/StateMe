<?php

namespace App\Tests\Domain\Incident\Repository;

use App\Domain\Incident\Repository\IncidentRepository;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IncidentRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testFindAll(): void
    {
        $incidents = array_values($this->loadFixture('incident/all'));
        $repository = self::getContainer()->get(IncidentRepository::class);
        self::assertSame($incidents, $repository->findAll());
    }
}
