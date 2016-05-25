<?php

namespace Alroniks\Repository\Domain\Category;

use Alroniks\Repository\AbstractRepository;
use Alroniks\Repository\Contracts\RepositoryInterface;
use Alroniks\Repository\Domain\DomainException;

/**
 * Repository of categories
 * @package Alroniks\Repository\Domain\Category
 */
class Categories extends AbstractRepository implements RepositoryInterface
{
    /**
     * @param object $entity
     * @return mixed
     * @throws DomainException
     */
    public function persist($entity)
    {
        // TODO: Implement persist() method.
    }

    /**
     * @param object $entity
     * @return mixed
     * @throws DomainException
     */
    public function remove($entity)
    {
        // TODO: Implement remove() method.
    }
}
