<?php

namespace Alroniks\Repository\Models\Repository;

class Storage extends \Alroniks\Repository\Models\Storage
{
    /**
     * @param Repository $repository
     */
    public function add(Repository $repository)
    {
        $this->persistence->persist($this->prefix . $repository->getId(), [
            $repository->getId(),
            $repository->getName(),
            $repository->getDescription(),
            $repository->getCreatedOn(),
            $repository->getRank(),
            $repository->getTemplated()
        ]);
    }
}
