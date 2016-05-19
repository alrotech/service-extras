<?php

namespace Alroniks\Repository\Models;

use Alroniks\Repository\Contracts\FactoryInterface;
use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Contracts\StorageInterface;
use Alroniks\Repository\InMemoryPersistence;

class Storage implements StorageInterface
{
    /** @var InMemoryPersistence */
    protected $persistence;

    /** @var FactoryInterface */
    protected $factory;

    /**
     * Storage constructor.
     * @param PersistenceInterface|null $persistence
     * @param FactoryInterface $factory
     */
    public function __construct(PersistenceInterface $persistence = null, FactoryInterface $factory)
    {
        $this->persistence = $persistence;
        $this->factory = $factory;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $elements = [];

        foreach ($this->persistence->retrieveAll() as $element) {
            $elements[] = $this->factory->make($element);
        }

        return $elements;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        $this->persistence->retrieve($id);
    }
}
