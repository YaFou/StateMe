<?php

namespace App\Domain\Service\Repository;

use App\Domain\Service\Entity\ServiceStatus;
use App\Domain\Shared\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class ServiceStatusRepository extends AbstractRepository
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
    public function findDefault(): ?ServiceStatus
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
    public function findFirst(): ?ServiceStatus
    {
        return $this->createQuery('status')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return ServiceStatus[]
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    public function findAll(): array
    {
        return $this->createQuery('status')
            ->getQuery()
            ->getResult();
    }

    protected function getEntityClass(): string
    {
        return ServiceStatus::class;
    }
}
