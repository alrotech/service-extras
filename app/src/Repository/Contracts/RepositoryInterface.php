<?php declare(strict_types = 1);

namespace Alroniks\Repository\Contracts;

use Alroniks\Repository\Domain\DomainException;
use Alroniks\Repository\Domain\RecordNotFoundException;

/**
 * Interface RepositoryInterface
 * @package Alroniks\Repository\Contracts
 */
interface RepositoryInterface
{
    /**
     * RepositoryInterface constructor.
     * @param StorageInterface $persistence
     * @param FactoryInterface $factory
     */
    public function __construct(StorageInterface $persistence, FactoryInterface $factory);

    /**
     * @param EntityInterface $entity
     * @return mixed
     * @throws DomainException
     */
    public function add(EntityInterface $entity) : EntityInterface;
    
    /**
     * @param EntityInterface $entity
     * @return bool
     * @throws DomainException
     */
    public function remove(EntityInterface $entity) : bool;

    /**
     * @param string $id
     * @return EntityInterface
     * @throws RecordNotFoundException
     */
    public function find(string $id) : EntityInterface;

    /**
     * @return array
     */
    public function findAll() : array;

    /**
     * @param string $field
     * @param $value
     * @return array
     */
    public function findBy(string $field, $value) : array;

    /**
     * @param int $perPage
     * @return mixed
     */
    public function paginate(int $perPage = 10);

}
