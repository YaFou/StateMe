<?php

namespace App\Application\Controller\ServiceStatus;

use App\Domain\Service\Repository\ServiceStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractServiceStatusController extends AbstractController
{
    public function __construct(private ServiceStatusRepository $repository)
    {
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $statuses = $this->repository->findAll();
        $parameters = array_merge(['statuses' => $statuses], $parameters);

        return parent::render($view, $parameters, $response);
    }
}
