<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Category;

use Alroniks\Repository\AbstractRepository;
use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\RepositoryInterface;
use Alroniks\Repository\Contracts\StorageInterface;

/**
 * Repository of categories
 * @package Alroniks\Repository\Domain\Category
 */
class Categories extends AbstractRepository implements RepositoryInterface
{
    /**
     * @return StorageInterface
     */
    protected function getStorage() : StorageInterface
    {
        $storage = parent::getStorage();
        $storage->setStorageKey(Category::class);

        return $storage;
    }

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
