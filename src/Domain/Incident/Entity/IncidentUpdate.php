<?php

namespace App\Domain\Incident\Entity;

use App\Domain\Service\Entity\ServiceUpdate;
use App\Domain\Shared\IdTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class IncidentUpdate
{
    use IdTrait;

    #[ORM\OneToMany(
        mappedBy: 'incidentUpdate',
        targetEntity: ServiceUpdate::class,
        cascade: ['all'],
        orphanRemoval: true
    )]
    private Collection $serviceUpdates;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Incident::class, inversedBy: 'updates')]
        private Incident $incident,
        #[ORM\Column(type: 'text')]
        private string $message,
        #[ORM\ManyToOne(targetEntity: IncidentStatus::class)]
        private IncidentStatus $status,
        #[ORM\Column(type: 'datetime_immutable')]
        private DateTimeImmutable $updatedAt
    ) {
        $this->incident->addUpdate($this);
        $this->serviceUpdates = new ArrayCollection();
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): IncidentUpdate
    {
        $this->message = $message;
        return $this;
    }

    public function getStatus(): IncidentStatus
    {
        return $this->status;
    }

    public function setStatus(IncidentStatus $status): IncidentUpdate
    {
        $this->status = $status;
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): IncidentUpdate
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function addServiceUpdate(ServiceUpdate $serviceUpdate): static
    {
        $this->serviceUpdates->add($serviceUpdate);

        return $this;
    }

    public function getServiceUpdates(): Collection
    {
        return $this->serviceUpdates;
    }
}
