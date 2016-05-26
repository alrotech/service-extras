<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Category;

use Alroniks\Repository\AbstractRepository;
use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\RepositoryInterface;

/**
 * Repository of categories
 * @package Alroniks\Repository\Domain\Category
 */
class Categories extends AbstractRepository implements RepositoryInterface
{
    /**
     * @param string $field
     * @param $value
     * @return EntityInterface[]
     */
    public function findBy(string $field, $value) : array
    {
        $value = $value instanceof EntityInterface ? $value->getId() : $value;

        return array_filter($this->findAll(), function ($category) use ($field, $value) {
            /** @var Category $category */
            $method = 'get' . ucfirst($field);
            return $category->$method() == $value;
        });
    }
}
