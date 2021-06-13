<?php

namespace App\Domain\Shared;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;

abstract class AbstractRepository implements ServiceEntityRepositoryInterface
{
    protected EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry)
    {
        $manager = $registry->getManagerForClass($this->getEntityClass());

        if (!$manager) {
            throw new LogicException(sprintf('Could not find entity manager for class "%s"', $this->getEntityClass()));
        }

        if (!$manager instanceof EntityManagerInterface) {
            throw new LogicException(
                sprintf(
                    'Object manager of class "%s" is not an instance of "%s"',
                    $this->getEntityClass(),
                    EntityManagerInterface::class
                )
            );
        }

        $this->manager = $manager;
    }

    abstract protected function getEntityClass(): string;

    protected function createQuery(string $entityName): QueryBuilder
    {
        return $this->manager->createQueryBuilder()
            ->from($this->getEntityClass(), $entityName)
            ->select($entityName);
    }
}
