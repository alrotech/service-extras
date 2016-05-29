<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Package;

use Alroniks\Repository\AbstractRepository;
use Alroniks\Repository\Contracts\RepositoryInterface;
use Alroniks\Repository\Contracts\StorageInterface;

/**
 * Class Repositories
 * @package Alroniks\Repository\Domain\Package
 */
class Packages extends AbstractRepository implements RepositoryInterface
{
    /**
     * @return StorageInterface
     */
    protected function getStorage() : StorageInterface
    {
        $storage = parent::getStorage();
        $storage->setStorageKey(Package::class);

        return $storage;
    }

}
