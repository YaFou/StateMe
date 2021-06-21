<?php

namespace App\Application\Controller\Service;

use App\Domain\Service\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractServiceController extends AbstractController
{
    public function __construct(private ServiceRepository $repository)
    {
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $services = $this->repository->findAll();
        $parameters = array_merge(['services' => $services], $parameters);

        return parent::render($view, $parameters, $response);
    }
}
