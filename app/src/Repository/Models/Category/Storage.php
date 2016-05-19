<?php

namespace Alroniks\Repository\Models\Category;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\InMemoryPersistence;

/**
 * Class Storage
 * @package Alroniks\Repository\Models\Category
 */
class Storage
{
    /** @var InMemoryPersistence */
    private $persistence;
    
    /** @var Factory */
    private $factory;

    /**
     * Storage constructor.
     * @param PersistenceInterface|null $persistence
     */
    public function __construct(PersistenceInterface $persistence = null)
    {
        $this->persistence = $persistence ?: new InMemoryPersistence();
        $this->factory = new Factory();
    }

    /**
     * @param Category $category
     */
    public function add(Category $category)
    {
        $this->persistence->persist([
            $category->getRepositoryId(),
            $category->getId(),
            $category->getName()
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return current(array_filter($this->findAll(), function ($category) use ($id) {
            /** @var Category $category */
            return $category->getId() == $id;
        }));
    }

    /**
     * @param $repositoryId
     * @return array
     */
    public function findByRepositoryId($repositoryId)
    {
        return array_filter($this->findAll(), function ($category) use ($repositoryId) {
            /** @var Category $category */
            return $category->getRepositoryId() == $repositoryId;
        });
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $repositories = [];

        foreach ($this->persistence->retrieveAll() as $repository) {
            $repositories[] = $this->factory->make($repository);
        }

        return $repositories;
    }
}
