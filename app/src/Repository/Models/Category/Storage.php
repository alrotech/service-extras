<?php

namespace Alroniks\Repository\Models\Category;

use Alroniks\Repository\Models\AbstractStorage;

/**
 * Class Storage
 * @package Alroniks\Repository\Models\Category
 */
class Storage extends AbstractStorage
{
    protected $prefix = 'category:';

    /**
     * @param $repositoryId
     * @return array
     */
    public function findByRepositoryId($repositoryId)
    {
        return array_filter($this->all(), function ($category) use ($repositoryId) {
            /** @var Category $category */
            return $category->getRepositoryId() == $repositoryId;
        });
    }

}
