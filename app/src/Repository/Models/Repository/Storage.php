<?php

namespace Alroniks\Repository\Models\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\InMemoryPersistence;

class Storage
{
    private $persistence;
    private $factory;

    public function __construct(PersistenceInterface $persistence = null)
    {
        $this->persistence = $persistence ?: new InMemoryPersistence();
        $this->factory = new Factory();
    }

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

    public function findAll()
    {
        $repositories = [];

        foreach ($this->persistence->retrieveAll() as $repository)
        {
            $repositories[] = $this->factory->make($repository);
        }

        return $repositories;
    }
}
