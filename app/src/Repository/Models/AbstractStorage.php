<?php

namespace Alroniks\Repository\Models;

use Alroniks\Repository\Contracts\DomainObjectInterface;
use Alroniks\Repository\Contracts\FactoryInterface;
use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Contracts\RepositoryInterface;

/**
 * Class Storage
 * Implements Repository Interface, but names as Storage because class name
 * is conflicting with class from Domain model
 * @package Alroniks\Repository\Models
 */
abstract class AbstractStorage implements RepositoryInterface
{
    protected $persistence;

    protected $prefix = '';

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
    public function all()
    {
        $elements = [];

        foreach ($this->persistence->collection($this->prefix . '*') as $key => $element) {

            $elements[] = $this->factory->make($element);
        }

        return $elements;
    }

    /**
     * @param DomainObjectInterface $object
     */
    public function create(DomainObjectInterface $object)
    {
        // todo: configure ttl
        $this->persistence->persist($this->prefix . $object->getId(), $object->__toArray(), 300);
    }

    /**
     * @param DomainObjectInterface $object
     * @param $id
     * @return mixed|void
     */
    public function update(DomainObjectInterface $object, $id)
    {
        $this->delete($id);
        $this->create($object);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->persistence->purge($this->prefix . $id);
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function find($id)
    {
        $object = $this->persistence->retrieve($this->prefix . $id);

        if ($object) {
            return $this->factory->make($object);
        }

        return null;
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed|void
     */
    public function findBy($field, $value, $columns = [])
    {
        return 0;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function exists($id)
    {
        return $this->persistence->exists($this->prefix . $id);
    }

}
