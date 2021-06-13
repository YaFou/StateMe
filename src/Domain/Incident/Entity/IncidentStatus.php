<?php

namespace App\Domain\Incident\Entity;

use App\Domain\Shared\IdTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class IncidentStatus
{
    use IdTrait;

    public function __construct(
        #[ORM\Column]
        private string $name,
        #[ORM\Column]
        private string $icon,
        #[ORM\Column(length: 8)]
        private string $color,
        #[ORM\Column(type: 'boolean')]
        private bool $default = false
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): IncidentStatus
    {
        $this->name = $name;
        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): IncidentStatus
    {
        $this->icon = $icon;
        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): IncidentStatus
    {
        $this->color = $color;
        return $this;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function setDefault(bool $default): IncidentStatus
    {
        $this->default = $default;
        return $this;
    }
}
