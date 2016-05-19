<?php

namespace Alroniks\Repository\Models\Category;

class Transformer
{
    public static function transform(Category $category)
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'packages' => 10, // generic todo count packages in repository?
        ];
    }
}
