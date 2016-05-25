<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Category;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\TransformerInterface;

/**
 * Class Transformer
 * @package Alroniks\Repository\Domain\Category
 */
class CategoryTransformer implements TransformerInterface
{
    /**
     * @param EntityInterface $entity
     * @return array
     */
    public static function transform(EntityInterface $entity) : array
    {
        /** @var Category $entity */
        $output = $entity->toArray();

        unset($output['repository']);
        $output['packages'] = 10; // todo

        return $output;
    }
}
