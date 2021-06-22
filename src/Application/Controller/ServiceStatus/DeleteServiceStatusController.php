<?php

namespace App\Application\Controller\ServiceStatus;

use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\ServiceStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteServiceStatusController extends AbstractController
{
    public function __construct(private ServiceStatusService $service)
    {
    }

    #[Route('/dashboard/service-statuses/{id<\d+>}/delete', name: 'service-status:delete')]
    public function __invoke(
        ServiceStatus $status,
        Request $request
    ) {
        if (
            $this->isCsrfTokenValid(
                sprintf('service-status:%d:delete', $status->getId()),
                $request->request->get('_token')
            )
        ) {
            $this->service->delete($status);
        }

        return $this->redirectToRoute('service-status:list');
    }
}
