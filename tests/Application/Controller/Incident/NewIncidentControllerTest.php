<?php

namespace App\Tests\Application\Controller\Incident;

use App\Domain\Incident\Repository\IncidentRepository;
use App\Tests\FixturesTrait;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewIncidentControllerTest extends WebTestCase
{
    use FixturesTrait;

    private const URL = '/dashboard/incidents/new';

    public function test(): void
    {
        $client = self::createClient();
        $this->loadFixture('incident/status/default');

        $client->request('GET', self::URL);
        $client->submitForm(
            'Report',
            [
                'create_incident[message]' => 'message',
                'create_incident[createdAt][date][month]' => '1',
                'create_incident[createdAt][date][day]' => '1',
                'create_incident[createdAt][date][year]' => '2024',
                'create_incident[createdAt][time][hour]' => '0',
                'create_incident[createdAt][time][minute]' => '0',
            ]
        );

        self::assertResponseRedirects('/dashboard/incidents/new');
        $repository = self::getContainer()->get(IncidentRepository::class);
        [$incident] = $repository->findAll();
        $update = $incident->getLastUpdate();
        self::assertNotNull($update);
        self::assertSame('message', $update->getMessage());
        self::assertEquals(new DateTimeImmutable('2024-01-01 00:00:00'), $update->getUpdatedAt());
    }
}
