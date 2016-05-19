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
        if (!$components[1]) {
            $components[1] = substr(md5(md5($components[2] . $components[0])), 0, 10);
        }

        return new Category(
            (integer)$components[0],
            (string)$components[1],
            (string)$components[2]
        );
    }
}
