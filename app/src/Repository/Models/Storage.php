<?php

namespace Alroniks\Repository\Models;

use Alroniks\Repository\Contracts\FactoryInterface;
use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Contracts\StorageInterface;
use Alroniks\Repository\InMemoryPersistence;

class Storage implements StorageInterface
{
    protected $prefix;
    
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
        
        $this->prefix = get_class($this) . ':';
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $elements = [];

        foreach ($this->persistence->retrieveAll() as $key => $element) {
            $prefix = explode(':', $key)[0] . ':';
            if ($prefix !== $this->prefix) {
                continue;
            }

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
        $entity = $this->persistence->retrieve($this->prefix . $id);

        if ($entity) {
            return $this->factory->make($entity);
        }

        return null;
    }
}
