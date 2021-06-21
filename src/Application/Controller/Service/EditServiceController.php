<?php

namespace App\Application\Controller\Service;

use App\Application\Form\ServiceType;
use App\Domain\Service\Dto\ServiceDto;
use App\Domain\Service\Entity\Service;
use App\Domain\Service\Repository\ServiceRepository;
use App\Domain\Service\ServiceService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditServiceController extends AbstractServiceController
{
    public function __construct(private ServiceRepository $repository, private ServiceService $service)
    {
        parent::__construct($this->repository);
    }

    #[Route('/dashboard/services/{id<\d+>}', name: 'service:edit')]
    public function __invoke(
        Request $request,
        Service $service
    ): Response {
        /** @var FormInterface<ServiceDto> $form */
        $form = $this->createForm(ServiceType::class, ServiceDto::fromService($service));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $data = $form->getData()) {
            $service = $this->service->update($service, $data);

            return $this->redirectToRoute('service:edit', ['id' => $service->getId()]);
        }

        return $this->render(
            'service/edit.html.twig',
            [
                'form' => $form->createView(),
                'service' => $service
            ]
        );
    }
}
