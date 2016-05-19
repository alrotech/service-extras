<?php

namespace Alroniks\Repository\Models\Repository;

use Alroniks\Repository\Contracts\FactoryInterface;
use DateTime;

/**
 * Class Factory
 * @package Alroniks\Repository\Models\Repository
 */
class Factory implements FactoryInterface
{
    /**
     * @param $components
     * @return Repository
     */
    public function make($components)
    {
        // generate unique identifier
        if (!$components[0]) {
            $components[0] = substr(md5(md5($components[1])), 0, 10);
        }

        return new Repository(
            $components[0],
            (string)$components[1],
            (string)$components[2],
            new DateTime((string)$components[3] ?: 'now'),
            (int)$components[4],
            (bool)$components[5]
        );
    }
}
