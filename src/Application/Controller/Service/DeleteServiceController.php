<?php

namespace App\Application\Controller\Service;

use App\Domain\Service\Entity\Service;
use App\Domain\Service\ServiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteServiceController extends AbstractController
{
    public function __construct(private ServiceService $service)
    {
    }

    #[Route('/dashboard/services/{id<\d+>}/delete', name: 'service:delete')]
    public function __invoke(
        Request $request,
        Service $service
    ): RedirectResponse {
        if (
        $this->isCsrfTokenValid(
            sprintf('service:%d:delete', $service->getId()),
            $request->request->get('_token')
        )
        ) {
            $this->service->delete($service);
        }

        return $this->redirectToRoute('service:list');
    }
}
