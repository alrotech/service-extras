<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Repository;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\FactoryInterface;

/**
 * Repository Factory
 * @package Alroniks\Repository\Domain\Repository
 */
class RepositoryFactory implements FactoryInterface
{
    /**
     * @param array $raw
     * @return EntityInterface
     */
    public function make(array $raw) : EntityInterface
    {
        return new Repository(
            (string)($raw['id'] ?? null),
            (string)($raw['name'] ?? 'no name'),
            (string)($raw['description'] ?? 'no description'),
            (string)($raw['createdon'] ?? ''),
            (int)($raw['rank'] ?? 0),
            (bool)($raw['templated'] ?? false)
        );
    }
}
