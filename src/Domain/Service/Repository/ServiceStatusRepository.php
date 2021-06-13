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
    public function getServiceStatusCount(): int
    {
        return (int)$this->createQuery('serviceStatus')
            ->select('COUNT(serviceStatus)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     * @throws NonUniqueResultException
     */
    public function findDefaultServiceStatus(): ?ServiceStatus
    {
        return $this->createQuery('serviceStatus')
            ->where('serviceStatus.default = true')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     * @throws NonUniqueResultException
     */
    public function findFirstServiceStatus(): ?ServiceStatus
    {
        return $this->createQuery('serviceStatus')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    protected function getEntityClass(): string
    {
        return ServiceStatus::class;
    }
}
