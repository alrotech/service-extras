<?php

namespace Alroniks\Repository\Contracts;

interface StorageInterface
{
    /**
     * StorageInterface constructor.
     * @param PersistenceInterface|null $persistence
     * @param FactoryInterface $factory
     */
    public function __construct(PersistenceInterface $persistence, FactoryInterface $factory);

    /**
     * @return mixed
     */
    public function findAll();

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);
    
}
