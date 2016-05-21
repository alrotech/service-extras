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
        if (!$components['id']) {
            $components['id'] = Repository::ID($components['name']);
        }

        return new Repository(
            $components['id'],
            (string)$components['name'],
            (string)$components['description'],
            $components['createdon'],
            (int)$components['rank'],
            (int)$components['templated']
        );
    }
}
