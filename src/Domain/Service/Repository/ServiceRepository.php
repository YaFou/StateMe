<?php

namespace App\Domain\Service\Repository;

use App\Domain\Service\Entity\Service;
use App\Domain\Shared\AbstractRepository;

class ServiceRepository extends AbstractRepository
{
    /**
     * @return Service[]
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function findAll(): array
    {
        return (array)$this->createQuery('service')
            ->getQuery()
            ->getResult();
    }

    protected function getEntityClass(): string
    {
        return Service::class;
    }
}
