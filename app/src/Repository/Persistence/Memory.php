<?php

// todo: maybe it can be replace by redis realisation?

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;

/**
 * Class Memory
 * @package Alroniks\Repository
 */
class Memory implements PersistenceInterface
{
    /** @var array */
    private $data = [];

    /**
     * @param $key
     * @param $data
     * @param int $ttl
     * @return mixed|void
     */
    public function persist($key, $data, $ttl = 0)
    {
        $this->data[$key] = $data;
    }
    
    public function purge($key)
    {
        // TODO: Implement purge() method.
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
     * @param $key
     * @return array
     */
    public function collection($key)
    {
        return $this->data;
    }
    
}
