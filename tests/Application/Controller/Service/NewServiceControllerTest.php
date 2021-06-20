<?php

namespace App\Tests\Application\Controller\Service;

use App\Domain\Service\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewServiceControllerTest extends WebTestCase
{
    private const URL = '/dashboard/services/new';

    public function test(): void
    {
        $client = self::createClient();
        $client->request('GET', self::URL);
        $client->submitForm(
            'New',
            [
                'service[name]' => 'name',
                'service[url]' => 'https://stateme.org'
            ]
        );

        $repository = self::getContainer()->get(ServiceRepository::class);
        [$service] = $repository->findAll();
        self::assertSame('name', $service->getName());
        self::assertSame('https://stateme.org', $service->getUrl());
        self::assertResponseRedirects(sprintf('/dashboard/services/%d', $service->getId()));
    }
}
