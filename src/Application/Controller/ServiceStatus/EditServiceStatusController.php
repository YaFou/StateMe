<?php

namespace App\Application\Controller\ServiceStatus;

use App\Application\Form\ServiceStatusType;
use App\Domain\Service\Dto\ServiceStatusDto;
use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Service\Repository\ServiceStatusRepository;
use App\Domain\Service\ServiceStatusService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditServiceStatusController extends AbstractServiceStatusController
{
    public function __construct(ServiceStatusRepository $repository, private ServiceStatusService $service)
    {
        parent::__construct($repository);
    }

    #[Route('/dashboard/service-statuses/{id<\d+>}', name: 'service-status:edit')]
    public function __invoke(
        ServiceStatus $status,
        Request $request
    ): Response {
        /** @var FormInterface<ServiceStatusDto> $form */
        $form = $this->createForm(ServiceStatusType::class, ServiceStatusDto::fromServiceStatus($status));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $data = $form->getData()) {
            $data->color = substr($data->color, 1);
            $status = $this->service->update($status, $data);

            return $this->redirectToRoute('service-status:edit', ['id' => $status->getId()]);
        }

        return $this->render(
            'service-status/edit.html.twig',
            [
                'form' => $form->createView(),
                'status' => $status
            ]
        );
    }
}
