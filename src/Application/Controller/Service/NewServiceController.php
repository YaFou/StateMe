<?php

namespace App\Application\Controller\Service;

use App\Application\Form\ServiceType;
use App\Domain\Service\Dto\ServiceDto;
use App\Domain\Service\Repository\ServiceRepository;
use App\Domain\Service\ServiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewServiceController extends AbstractController
{
    public function __construct(private ServiceRepository $repository, private ServiceService $service)
    {
    }

    #[Route('/dashboard/services/new', name: 'service:new')]
    public function __invoke(
        Request $request
    ): Response {
        /** @var FormInterface<ServiceDto> $form */
        $form = $this->createForm(ServiceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $data = $form->getData()) {
            $this->service->create($data);
            // TODO
        }

        return $this->render(
            'service/new.html.twig',
            [
                'services' => $this->repository->findAll(),
                'form' => $form->createView()
            ]
        );
    }
}
