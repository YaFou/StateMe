<?php

namespace App\Domain\Service\Entity;

use App\Domain\Shared\IdTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ServiceStatus
{
    use IdTrait;

    public function __construct(
        #[ORM\Column]
        private string $name,
        #[ORM\Column]
        private string $icon,
        #[ORM\Column(length: 8)]
        private string $color,
        #[ORM\Column(name: '`default`', type: 'boolean')]
        private bool $default = false
    ) {
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

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function setDefault(bool $default): static
    {
        $this->default = $default;
        return $this;
    }
}
