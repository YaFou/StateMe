<?php

namespace App\Tests\Application\Controller\Service;

use App\Domain\Service\Entity\Service;
use App\Domain\Service\Repository\ServiceRepository;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditServiceControllerTest extends WebTestCase
{
    use FixturesTrait;

    private const URL = '/dashboard/services/%s';

    public function test(): void
    {
        $client = self::createClient();
        /** @var Service $service */
        ['service' => $service] = $this->loadFixture('service/one');

        $client->request('GET', sprintf(self::URL, $service->getId()));
        $client->submitForm(
            'Update',
            [
                'service[name]' => 'new name',
                'service[url]' => 'https://new-url.org'
            ]
        );

        $repository = self::getContainer()->get(ServiceRepository::class);
        [$service] = $repository->findAll();
        self::assertSame('new name', $service->getName());
        self::assertSame('https://new-url.org', $service->getUrl());
        self::assertResponseRedirects(sprintf('/dashboard/services/%d', $service->getId()));
    }
}
