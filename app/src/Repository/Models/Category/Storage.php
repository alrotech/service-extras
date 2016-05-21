<?php

namespace Alroniks\Repository\Models\Category;

use Alroniks\Repository\Models\AbstractStorage;

/**
 * Class Storage
 * @package Alroniks\Repository\Models\Category
 */
class Storage extends AbstractStorage
{
    /**
     * @param Category $category
     */
    public function add(Category $category)
    {
        $this->persistence->persist($this->prefix . $category->getId(), [
            $category->getRepositoryId(),
            $category->getId(),
            $category->getName()
        ]);
    }

    /**
     * @param $repositoryId
     * @return array
     */
    public function findByRepositoryId($repositoryId)
    {
        return array_filter($this->findAll(), function ($category) use ($repositoryId) {
            /** @var Category $category */
            return $category->getRepositoryId() == $repositoryId;
        });
    }

}
