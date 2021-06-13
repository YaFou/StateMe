<?php

namespace App\Domain\Incident\Entity;

use App\Domain\Shared\IdTrait;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class IncidentUpdate
{
    use IdTrait;

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
}
