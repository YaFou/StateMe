<?php

namespace App\Domain\Incident;

use App\Domain\Incident\Dto\CreateIncidentUpdateDto;
use App\Domain\Incident\Dto\UpdateIncidentUpdateDto;
use App\Domain\Incident\Entity\Incident;
use App\Domain\Incident\Entity\IncidentUpdate;
use App\Domain\Service\Dto\CreateServiceUpdateDto;
use App\Domain\Service\ServiceUpdateService;
use Doctrine\ORM\EntityManagerInterface;

class IncidentUpdateService
{
    public function __construct(
        private EntityManagerInterface $manager,
        private ServiceUpdateService $serviceUpdateService
    ) {
    }

    public function update(IncidentUpdate $update, UpdateIncidentUpdateDto $data): IncidentUpdate
    {
        $update->setMessage($data->message)
            ->setStatus($data->status)
            ->setUpdatedAt($data->updatedAt);

        $this->manager->flush();

        return $update;
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function create(Incident $incident, CreateIncidentUpdateDto $data): IncidentUpdate
    {
        $update = new IncidentUpdate($incident, $data->message, $data->status, $data->updatedAt);
        $this->manager->persist($update);

        foreach ($data->serviceUpdates as $serviceUpdate) {
            $serviceUpdateData = new CreateServiceUpdateDto();
            $serviceUpdateData->service = $serviceUpdate[0];
            $serviceUpdateData->status = $serviceUpdate[1];
            $this->serviceUpdateService->create($update, $serviceUpdateData);
        }

        $this->manager->flush();

        return $update;
    }

    public function delete(IncidentUpdate $update): void
    {
        $this->manager->remove($update);
        $this->manager->flush();
    }
}
