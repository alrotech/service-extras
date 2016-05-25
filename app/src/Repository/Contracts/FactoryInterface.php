<?php declare(strict_types = 1);

namespace Alroniks\Repository\Contracts;

/**
 * Interface FactoryInterface
 * @package Alroniks\Repository\Contracts
 */
interface FactoryInterface
{
    /**
     * Creates entity from raw data
     * @param array $raw
     * @return EntityInterface
     */
    public function make(array $raw) : EntityInterface;
}
