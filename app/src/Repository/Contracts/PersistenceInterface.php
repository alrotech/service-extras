<?php

namespace Alroniks\Repository\Contracts;

/**
 * Interface PersistenceInterface
 * @package Alroniks\Repository\Contracts
 */
interface PersistenceInterface
{
    /**
     * @param $key
     * @param $data
     * @param $ttl
     * @return
     */
    public function persist($key, $data, $ttl = 0);

    /**
     * @param $key
     * @return mixed
     */
    public function purge($key);

    /**
     * @param $key
     * @return mixed
     */
    public function retrieve($key);

    /**
     * @param $key
     * @return mixed
     */
    public function collection($key);
}
