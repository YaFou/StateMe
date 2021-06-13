<?php

namespace App\Domain\Incident\Repository;

use App\Domain\Incident\Entity\IncidentStatus;
use App\Domain\Shared\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class IncidentStatusRepository extends AbstractRepository
{
    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getIncidentStatusCount(): int
    {
        return (int)$this->createQuery('incidentStatus')
            ->select('COUNT(incidentStatus)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     * @throws NonUniqueResultException
     */
    public function findDefaultIncidentStatus(): ?IncidentStatus
    {
        return $this->createQuery('incidentStatus')
            ->where('incidentStatus.default = true')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     * @throws NonUniqueResultException
     */
    public function findFirstIncidentStatus(): ?IncidentStatus
    {
        return $this->createQuery('incidentStatus')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    protected function getEntityClass(): string
    {
        return IncidentStatus::class;
    }
}
