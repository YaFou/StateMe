<?php

namespace App\Application\Controller\ServiceStatus;

use App\Application\Form\ServiceStatusType;
use App\Domain\Service\Dto\ServiceStatusDto;
use App\Domain\Service\Repository\ServiceStatusRepository;
use App\Domain\Service\ServiceStatusService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewServiceStatusController extends AbstractServiceStatusController
{
    public function __construct(ServiceStatusRepository $repository, private ServiceStatusService $service)
    {
        parent::__construct($repository);
    }

    #[Route('/dashboard/service-statuses/new', name: 'service-status:new')]
    public function __invoke(
        Request $request
    ): Response {
        /** @var FormInterface<ServiceStatusDto> $form */
        $form = $this->createForm(ServiceStatusType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $data = $form->getData()) {
            $data->color = substr($data->color, 1);
            $status = $this->service->create($data);

            return $this->redirectToRoute('service-status:edit', ['id' => $status->getId()]);
        }

        return $this->render('service-status/new.html.twig', ['form' => $form->createView()]);
    }
}
