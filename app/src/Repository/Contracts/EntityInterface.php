<?php declare(strict_types = 1);

namespace Alroniks\Repository\Contracts;

/**
 * Interface EntityInterface
 * @package Alroniks\Repository\Contracts
 */
interface EntityInterface
{
    /**
     * Returns ID of entity
     * @return string
     */
    public function getId() : string;

    /**
     * Return object as an array, like hash table
     * @return array
     */
    public function toArray() : array;
}
