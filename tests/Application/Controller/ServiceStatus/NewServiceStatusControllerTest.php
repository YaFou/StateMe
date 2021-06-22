<?php

namespace App\Tests\Application\Controller\ServiceStatus;

use App\Domain\Service\Repository\ServiceStatusRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewServiceStatusControllerTest extends WebTestCase
{
    private const URL = '/dashboard/service-statuses/new';

    public function test(): void
    {
        $client = self::createClient();
        $client->request('GET', self::URL);
        $client->submitForm(
            'New',
            [
                'service_status[name]' => 'name',
                'service_status[icon]' => 'icon',
                'service_status[color]' => '#000000'
            ]
        );

        $repository = self::getContainer()->get(ServiceStatusRepository::class);
        [$status] = $repository->findAll();
        self::assertSame('name', $status->getName());
        self::assertSame('icon', $status->getIcon());
        self::assertSame('000000', $status->getColor());
        self::assertResponseRedirects(sprintf('/dashboard/service-statuses/%d', $status->getId()));
    }
}
