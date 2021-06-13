<?php

namespace App\Domain\Service\Entity;

use App\Domain\Shared\IdTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Service
{
    use IdTrait;

    public function __construct(
        #[ORM\Column]
        private string $name,
        #[ORM\Column(nullable: true)]
        private ?string $url = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Service
    {
        $this->name = $name;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): Service
    {
        $this->url = $url;
        return $this;
    }
}
