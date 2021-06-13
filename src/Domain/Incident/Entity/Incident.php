<?php

namespace App\Domain\Incident\Entity;

use App\Domain\Shared\IdTrait;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Incident
{
    use IdTrait;

    public function __construct(
        #[ORM\Column]
        private string $name,
        #[ORM\Column(type: 'datetime_immutable')]
        private DateTimeImmutable $createdAt,
        #[ORM\Column(nullable: true)]
        private ?string $description = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Incident
    {
        $this->name = $name;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): Incident
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Incident
    {
        $this->description = $description;
        return $this;
    }
}
