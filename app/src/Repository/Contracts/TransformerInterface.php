<?php declare(strict_types = 1);

namespace Alroniks\Repository\Contracts;

/**
 * Interface TransformerInterface
 * @package Alroniks\Repository\Contracts
 */
interface TransformerInterface
{
    /**
     * @param EntityInterface $entity
     * @return array
     */
    public static function transform(EntityInterface $entity) : array;
}
