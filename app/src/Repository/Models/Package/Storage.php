<?php

namespace Alroniks\Repository\Models\Package;

use Alroniks\Repository\Models\AbstractStorage;

class Storage extends AbstractStorage
{
    /**
     * @param Package $package
     */
    public function add(Package $package)
    {
        $this->persistence->persist($this->prefix . $package->getId(), [
            $package->getCategoryId(),
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
            $package->getDownloads(),
            $package->getPackage()
        ]);
    }

}
