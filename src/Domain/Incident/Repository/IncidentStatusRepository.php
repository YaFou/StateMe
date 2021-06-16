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
    public function count(): int
    {
        return (int)$this->createQuery('status')
            ->select('COUNT(status)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     * @throws NonUniqueResultException
     */
    public function findDefault(): ?IncidentStatus
    {
        return $this->createQuery('status')
            ->where('status.default = true')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     * @throws NonUniqueResultException
     */
    public function findFirst(): ?IncidentStatus
    {
        return $this->createQuery('status')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    protected function getEntityClass(): string
    {
        return IncidentStatus::class;
    }
}
