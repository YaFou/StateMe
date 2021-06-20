<?php

namespace App\Application\Controller\Service;

use App\Domain\Service\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceListController extends AbstractController
{
    public function __construct(private ServiceRepository $repository)
    {
    }

    #[Route('/dashboard/services', name: 'service:list')]
    public function __invoke(): Response
    {
        $services = $this->repository->findAll();

        return $this->render('service/index.html.twig', ['services' => $services]);
    }
}
