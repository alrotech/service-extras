<?php declare(strict_types = 1);
/**
 * Copyright (c) 2016 Alroniks Experts LLC
 *
 * @author: Ivan Klimchuk <ivan@klimchuk.com>
 * @package: AES Repository
 */

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\FactoryInterface;
use Alroniks\Repository\Contracts\StorageInterface;
use Alroniks\Repository\Contracts\RepositoryInterface;
use Alroniks\Repository\Domain\DomainException;
use Alroniks\Repository\Domain\RecordNotFoundException;

/**
 * Class AbstractRepository
 * @package Alroniks\Repository
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /** @var StorageInterface */
    private $persistence;

    /** @var FactoryInterface */
    private $factory;

    private $offset = null;

    private $limit = null;

    /**
     * AbstractRepository constructor.
     * @param StorageInterface $persistence
     * @param FactoryInterface $factory
     */
    public function __construct(StorageInterface $persistence, FactoryInterface $factory)
    {
        $this->persistence = $persistence;
        $this->factory = $factory;
    }

    /**
     * Returns current storage implementation
     * @return StorageInterface
     */
    protected function getStorage() : StorageInterface
    {
        return $this->persistence;
    }

    /**
     * Returns factory for creating entities
     * @return FactoryInterface
     */
    protected function getFactory() : FactoryInterface
    {
        return $this->factory;
    }

    /**
     * @param EntityInterface $entity
     * @return EntityInterface
     * @throws DomainException
     */
    public function add(EntityInterface $entity) : EntityInterface
    {
        $this->getStorage()->persist($entity->toArray());

        return $entity;
    }

    /**
     * @param EntityInterface $entity
     * @return bool
     */
    public function remove(EntityInterface $entity) : bool
    {
        return $this->getStorage()->delete($entity->getId());
    }

    /**
     * @param string $id
     * @return EntityInterface
     */
    public function find(string $id) : EntityInterface
    {
        if (!$raw = $this->getStorage()->retrieve($id)) {
            throw new RecordNotFoundException();
        }

        return $this->getFactory()->make($raw);
    }

    /**
     * @return array
     */
    public function findAll() : array
    {
        $entities = !is_null($this->limit) && !is_null($this->offset)
            ? $this->getStorage()->search()->take($this->limit, $this->offset)
            : $this->getStorage()->search()->all();
        
        foreach ($entities as &$raw) {
            $raw = $this->getFactory()->make($raw);
        }

        return $entities;
    }

    /**
     * @param string $field
     * @param $value
     * @return array
     */
    public function findBy(string $field, $value) : array
    {
        $entities = $this->limit && $this->offset
            ? $this->getStorage()->search($field, $value)->take($this->limit, $this->offset)
            : $this->getStorage()->search($field, $value)->all();

        foreach ($entities as &$raw) {
            $raw = $this->getFactory()->make($raw);
        }

        return $entities ?: [];
    }

    /**
     * @param int $currentPage
     * @param int $perPage
     * @return array
     */
    public function paginate(int $currentPage, int $perPage = 10) : array
    {
        $currentPage++; // because started from 0

        $total = $this->getStorage()->count();
        $totalPages = ceil($total / $perPage);

        $this->limit = $perPage;
        $this->offset = intval($currentPage * $perPage - $perPage);

        return [
            'type' => 'array',
            'total' => $total,
            'page' => $currentPage,
            'of' => $totalPages
        ];
    }
}
