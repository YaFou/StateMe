<?php

namespace App\Application\Controller\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceListController extends AbstractServiceController
{
    #[Route('/dashboard/services', name: 'service:list')]
    public function __invoke(): Response
    {
        return $this->render('service/index.html.twig');
    }
}
