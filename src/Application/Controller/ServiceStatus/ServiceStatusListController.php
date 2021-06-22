<?php

namespace App\Application\Controller\ServiceStatus;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceStatusListController extends AbstractServiceStatusController
{
    #[Route('/dashboard/service-statuses', name: 'service-status:list')]
    public function __invoke(): Response
    {
        return $this->render('service-status/index.html.twig');
    }
}
