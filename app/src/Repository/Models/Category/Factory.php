<?php

namespace Alroniks\Repository\Models\Category;

use Alroniks\Repository\Contracts\FactoryInterface;

/**
 * Class Factory
 * @package Alroniks\Repository\Models\Category
 */
class Factory implements FactoryInterface
{
    /**
     * @param $components
     * @return Category
     */
    public function make($components)
    {
        // generate unique identifier
        if (!$components['id']) {
            $components['id'] = Category::ID($components['repositoryId'] . $components['name']);
        }

        return new Category(
            (string)$components['repositoryId'],
            (string)$components['id'],
            (string)$components['name']
        );
    }
}
