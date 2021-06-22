<?php

namespace App\Tests\Application\Controller\ServiceStatus;

use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Repository\ServiceRepository;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DeleteServiceStatusControllerTest extends WebTestCase
{
    use FixturesTrait;

    private const URL = '/dashboard/service-statuses/%s/delete';

    public function test(): void
    {
        // TODO
        $this->markTestIncomplete(
            'Bug about CSRF tokens in test environment (see https://github.com/symfony/symfony/issues/41757)'
        );

        $client = self::createClient();
        /** @var ServiceStatus $status */
        ['service-status' => $status] = $this->loadFixture('service/status/one');

        /** @var CsrfTokenManagerInterface $tokenManager */
        $tokenManager = self::getContainer()->get('security.csrf.token_manager');
        $token = $tokenManager->getToken(sprintf('service-status:%d:delete', $status->getId()))->getValue();

        $client->request('DELETE', sprintf(self::URL, $status->getId()), parameters: ['_token' => $token]);
        $repository = self::getContainer()->get(ServiceRepository::class);
        self::assertEmpty($repository->findAll());
        self::assertResponseRedirects('/dashboard/service-statuses');
    }
}
