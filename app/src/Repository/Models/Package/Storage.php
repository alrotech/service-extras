<?php

namespace Alroniks\Repository\Models\Package;

class Storage extends \Alroniks\Repository\Models\Storage
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
