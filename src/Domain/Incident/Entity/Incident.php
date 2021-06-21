<?php

namespace App\Domain\Incident\Entity;

use App\Domain\Shared\IdTrait;
use ArrayIterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Incident
{
    use IdTrait;

    #[ORM\OneToMany(mappedBy: 'incident', targetEntity: IncidentUpdate::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $updates;

    public function __construct()
    {
        $this->updates = new ArrayCollection();
    }

    public function addUpdate(IncidentUpdate $update): static
    {
        $this->updates->add($update);

        return $this;
    }

    public function getLastUpdate(): ?IncidentUpdate
    {
        /** @var ArrayIterator $updates */
        $updates = $this->updates->getIterator();

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $updates->uasort(
            fn(
                IncidentUpdate $updateA,
                IncidentUpdate $updateB
            ) => $updateB->getUpdatedAt()->getTimestamp() - $updateA->getUpdatedAt()->getTimestamp()
        );

        /** @var Collection<int, IncidentUpdate> $sortedUpdates */
        $sortedUpdates = new ArrayCollection($updates->getArrayCopy());

        return $sortedUpdates->first() ?: null;
    }
}
