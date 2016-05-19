<?php

namespace Alroniks\Repository\Models\Category;

/**
 * Class Transformer
 * @package Alroniks\Repository\Models\Category
 */
class Transformer
{
    /**
     * @param Category $category
     * @return array
     */
    public static function transform(Category $category)
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'packages' => 10, // generic todo count packages in repository?
        ];
    }
}
