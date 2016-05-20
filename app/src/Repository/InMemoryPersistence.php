<?php

// todo: maybe it can be replace by redis realisation?

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;

class InMemoryPersistence implements PersistenceInterface
{
    /** @var array */
    private $data = [];

    /**
     * @param $key
     * @param $data
     * @return mixed|void
     */
    public function persist($key, $data)
    {
        $this->data[$key] = $data;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function retrieve($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
    }

    /**
     * @return array
     */
    public function retrieveAll()
    {
        return $this->data;
    }

}
