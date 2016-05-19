<?php

namespace Alroniks\Repository\Models\Package;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\InMemoryPersistence;

class Storage 
{
    /** @var InMemoryPersistence */
    private $persistence;
    
    public function __construct(PersistenceInterface $persistence = null)
    {
        $this->persistence = $persistence ?: new InMemoryPersistence();
        $this->factory = new Factory();
    }

    /**
     * @param Package $package
     */
    public function add(Package $package)
    {
        $this->persistence->persist([
            $package->getId(),
            $package->getName(),
            $package->getVersion(),
            $package->getAuthor(),
            $package->getLicense(),
            $package->getDescription(),
            $package->getInstructions(),
            $package->getChangelog(),
            $package->getCreatedon(),
            $package->getEditedon(),
            $package->getReleasedon(),
            $package->getCover(),
            $package->getThumb(),
            $package->getMinimum(),
            $package->getMaximum(),
            $package->getDatabases(),
            $package->getPackage()
        ]);
    }

    /**
     * @return Package[]
     */
    public function findAll()
    {
        $packages = [];

        foreach ($this->persistence->retrieveAll() as $package) {
            $packages[] = $this->factory->make($package);
        }

        return $packages;
    }

    /**
     * @param $id
     * @return Package
     */
    public function findById($id)
    {
        return current(array_filter($this->findAll(), function ($package) use ($id) {
            /** @var Package $package */
            return $package->getId() == $id;
        }));
    }
}
