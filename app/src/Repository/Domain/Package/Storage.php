<?php

namespace Alroniks\Repository\Models\Package;

use Alroniks\Repository\Models\AbstractStorage;

class Storage extends AbstractStorage
{
    protected $prefix = 'package:';

    /**
     * @param $categoryId
     * @return array
     */
    public function findByCategory($categoryId) {
        return array_filter($this->all(), function ($package) use ($categoryId) {
            /** @var Package $package */
            return $package->getCategoryId() == $categoryId;
        });
    }
}
