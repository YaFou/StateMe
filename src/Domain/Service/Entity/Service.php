<?php

namespace App\Domain\Service\Entity;

use App\Domain\Shared\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Service
{
    use IdTrait;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ServiceUpdate::class, orphanRemoval: true)]
    private Collection $updates;

    public function __construct(
        #[ORM\Column]
        private string $name,
        #[ORM\Column(nullable: true)]
        private ?string $url = null
    ) {
        $this->updates = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function addUpdate(ServiceUpdate $update): static
    {
        $this->updates->add($update);

        return $this;
    }
}
