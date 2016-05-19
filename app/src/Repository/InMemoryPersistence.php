<?php

// todo: maybe it can be replace by redis realisation?

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;

class InMemoryPersistence implements PersistenceInterface
{
    private $data = [];

    public function persist($data)
    {
        $this->data[] = $data;
    }

    public function retrieve($key)
    {
        return $this->data[$key];
    }

    public function retrieveAll()
    {
        return $this->data;
    }

}
