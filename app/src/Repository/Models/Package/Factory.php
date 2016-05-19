<?php

namespace Alroniks\Repository\Models\Package;

use Alroniks\Repository\Contracts\FactoryInterface;
use DateTime;

/**
 * Class Factory
 * @package Alroniks\Repository\Models\Package
 */
class Factory implements FactoryInterface
{
    /**
     * @param $components
     * @return Package
     */
    public function make($components)
    {
        // generate unique identifier
        if (!$components[0]) {
            $components[0] = substr(md5(md5($components[1] . $components[2] . 'pl')), 0, 10);
        }

        return new Package(
            (string)$components[0],
            (string)$components[1],
            (string)$components[2],
        
            // author and licence
            (string)$components[3],
            (string)$components[4],

            // full text materials
            (string)$components[5],
            (string)$components[6],
            (string)$components[7],
        
            // dates
            new DateTime((string)$components[8] ?: 'now'),
            new DateTime((string)$components[9] ?: 'now'),
            new DateTime((string)$components[10] ?: 'now'),
        
            // images (covers)
            (string)$components[11],
            (string)$components[12],

            // support
            (string)$components[13],
            (string)$components[14],
            (string)$components[15],
        
            // package
            (string)$components[16]
        );
    }
}
