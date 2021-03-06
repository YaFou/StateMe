<?php

namespace App\Domain\Service\Entity;

use App\Domain\Incident\Entity\IncidentUpdate;
use App\Domain\Shared\IdTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ServiceUpdate
{
    use IdTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: IncidentUpdate::class, inversedBy: 'serviceUpdates')]
        private IncidentUpdate $incidentUpdate,
        #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'updates')]
        private Service $service,
        #[ORM\ManyToOne(targetEntity: ServiceStatus::class)]
        private ServiceStatus $status
    ) {
        $this->incidentUpdate->addServiceUpdate($this);
        $this->service->addUpdate($this);
    }

    public function getStatus(): ServiceStatus
    {
        return $this->status;
    }

    public function setStatus(ServiceStatus $status): static
    {
        $this->status = $status;
        return $this;
    }
}
