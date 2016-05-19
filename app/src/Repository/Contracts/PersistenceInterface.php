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
     * @return mixed
     */
    public function persist($key, $data);

    /**
     * @param $key
     * @return mixed
     */
    public function retrieve($key);

    /**
     * @return mixed
     */
    public function retrieveAll();
}
