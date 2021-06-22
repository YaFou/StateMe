<?php

namespace App\Domain\Incident\Repository;

use App\Domain\Incident\Entity\Incident;
use App\Domain\Shared\AbstractRepository;

class IncidentRepository extends AbstractRepository
{
    /**
     * @return Incident[]
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    public function findAll(): array
    {
        return $this->createQuery('incident')
            ->getQuery()
            ->getResult();
    }

    protected function getEntityClass(): string
    {
        return Incident::class;
    }
}
