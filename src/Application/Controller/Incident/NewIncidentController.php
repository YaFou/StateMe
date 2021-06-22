<?php

namespace App\Application\Controller\Incident;

use App\Application\Form\Incident\CreateIncidentType;
use App\Domain\Incident\Dto\CreateIncidentDto;
use App\Domain\Incident\IncidentService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewIncidentController extends AbstractController
{
    public function __construct(private IncidentService $service)
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/dashboard/incidents/new', name: 'incident:new')]
    public function __invoke(Request $request): Response
    {
        /** @var FormInterface<CreateIncidentDto> $form */
        $form = $this->createForm(CreateIncidentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $data = $form->getData()) {
            $this->service->create($data);

            // TODO
            return $this->redirectToRoute('incident:new');
        }

        return $this->render('incident/new.html.twig', ['form' => $form->createView()]);
    }
}
