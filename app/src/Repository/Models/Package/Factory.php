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
        if (!$components[1]) {
            $components[1] = substr(md5(md5($components[2] . $components[3] . 'pl')), 0, 10);
        }

        return new Package(
            (string)$components[0],

            (string)$components[1],
            (string)$components[2],
            (string)$components[3],
        
            // author and licence
            (string)$components[4],
            (string)$components[5],

            // full text materials
            (string)$components[6],
            (string)$components[7],
            (string)$components[8],
        
            // dates
            $components[9],
            $components[10],
            $components[11],
        
            // images (covers)
            (string)$components[12],
            (string)$components[13],

            // support
            (string)$components[14],
            (string)$components[15],
            (string)$components[16],
        
            // package
            (string)$components[17]
        );
    }
}
