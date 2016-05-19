<?php

namespace Alroniks\Repository\Models\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\InMemoryPersistence;

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
     * @param Repository $repository
     */
    public function add(Repository $repository)
    {
        $this->persistence->persist([
            $repository->getId(),
            $repository->getName(),
            $repository->getDescription(),
            $repository->getCreatedOn(),
            $repository->getRank(),
            $repository->getTemplated()
        ]);
    }

    /**
     * @return Repository[]
     */
    public function findAll()
    {
        $repositories = [];

        foreach ($this->persistence->retrieveAll() as $repository) {
            $repositories[] = $this->factory->make($repository);
        }

        return $repositories;
    }

    /**
     * @param $id
     * @return Repository
     */
    public function findById($id)
    {
        return current(array_filter($this->findAll(), function ($repository) use ($id) {
            /** @var Repository $repository */
            return $repository->getId() == $id;
        }));
    }
}
