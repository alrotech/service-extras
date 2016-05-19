<?php

namespace Alroniks\Repository\Contracts;

/**
 * Interface FactoryInterface
 * @package Alroniks\Repository\Contracts
 */
interface FactoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function make($data);
}
