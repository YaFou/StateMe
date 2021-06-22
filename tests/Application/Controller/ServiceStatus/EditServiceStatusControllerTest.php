<?php

namespace App\Tests\Application\Controller\ServiceStatus;

use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Repository\ServiceStatusRepository;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditServiceStatusControllerTest extends WebTestCase
{
    use FixturesTrait;

    private const URL = '/dashboard/service-statuses/%s';

    public function test(): void
    {
        $client = self::createClient();
        /** @var ServiceStatus $status */
        ['service-status' => $status] = $this->loadFixture('service/status/one');

        $client->request('GET', sprintf(self::URL, $status->getId()));
        $client->submitForm(
            'Update',
            [
                'service_status[name]' => 'new name',
                'service_status[icon]' => 'new icon',
                'service_status[color]' => '#FFFFFF'
            ]
        );

        $repository = self::getContainer()->get(ServiceStatusRepository::class);
        [$status] = $repository->findAll();
        self::assertSame('new name', $status->getName());
        self::assertSame('new icon', $status->getIcon());
        self::assertSame('FFFFFF', $status->getColor());
        self::assertResponseRedirects(sprintf('/dashboard/service-statuses/%d', $status->getId()));
    }
}
