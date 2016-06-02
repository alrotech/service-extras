<?php declare(strict_types = 1);
/**
 * Copyright (c) 2016 Alroniks Experts LLC
 *
 * @author: Ivan Klimchuk <ivan@klimchuk.com>
 * @package: AES Repository
 */

namespace Alroniks\Repository\Domain\Repository;

use Alroniks\Repository\AbstractRepository;
use Alroniks\Repository\Contracts\RepositoryInterface;
use Alroniks\Repository\Contracts\StorageInterface;

/**
 * Class Repositories
 * @package Alroniks\Repository\Domain\Repository
 */
class Repositories extends AbstractRepository implements RepositoryInterface
{
    protected $searchable = [];

    /**
     * @return StorageInterface
     */
    protected function getStorage() : StorageInterface
    {
        $storage = parent::getStorage();
        $storage->setConfig([
            'key.storage' => strtoupper((new \ReflectionClass(Repository::class))->getShortName()),
            'fields' => $this->searchable
        ]);

        return $storage;
    }

}
