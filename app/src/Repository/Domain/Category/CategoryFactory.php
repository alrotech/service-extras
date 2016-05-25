<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Category;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\FactoryInterface;

/**
 * Class Factory
 * @package Alroniks\Repository\Domain\Category
 */
class CategoryFactory implements FactoryInterface
{
    /**
     * @param array $raw
     * @return EntityInterface
     */
    public function make(array $raw) : EntityInterface
    {
        return new Category(
            (string)($raw['repository'] ?? null),
            (string)($raw['id'] ?? null),
            (string)($raw['name'] ?? 'no name')
        );
    }
}
