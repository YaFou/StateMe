<?php

namespace App\Tests\Application\Controller\Service;

use App\Domain\Service\Entity\Service;
use App\Domain\Service\Repository\ServiceRepository;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DeleteServiceControllerTest extends WebTestCase
{
    use FixturesTrait;

    private const URL = '/dashboard/services/%s/delete';

    public function test(): void
    {
        // TODO
        $this->markTestSkipped(
            'Bug about CSRF tokens in test environment (see https://github.com/symfony/symfony/issues/41757)'
        );

        $client = self::createClient();
        /** @var Service $service */
        ['service' => $service] = $this->loadFixture('service/one');

        /** @var CsrfTokenManagerInterface $tokenManager */
        $tokenManager = self::getContainer()->get('security.csrf.token_manager');
        $token = $tokenManager->getToken(sprintf('service:%d:delete', $service->getId()))->getValue();

        $client->request('DELETE', sprintf(self::URL, $service->getId()), parameters: ['_token' => $token]);
        $repository = self::getContainer()->get(ServiceRepository::class);
        self::assertEmpty($repository->findAll());
        self::assertResponseRedirects('/dashboard/services');
    }
}
