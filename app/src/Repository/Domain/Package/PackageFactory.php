<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Package;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Contracts\FactoryInterface;

/**
 * Class PackageFactory
 * @package Alroniks\Repository\Domain\Package
 */
class PackageFactory implements FactoryInterface
{
    /**
     * @param array $raw
     * @return EntityInterface
     */
    public function make(array $raw) : EntityInterface
    {
        // generate unique identifier
        if (!$components['id']) {
            $components['id'] = Package::ID($components['category'] . $components['storage']);
        }

        return new Package(
            (string)$components['category'],

            (string)$components['id'],
            (string)$components['name'],
            (string)$components['version'],
            (string)$components['signature'],
        
            // author and licence
            (string)$components['author'],
            (string)$components['license'],

            // full text materials
            (string)$components['description'],
            (string)$components['instructions'],
            (string)$components['changelog'],
        
            // dates
            $components['createdon'],
            $components['editedon'],
            $components['releasedon'],
        
            // images (covers)
            (string)$components['cover'],
            (string)$components['thumb'],

            // support
            (string)$components['minimum'],
            (string)$components['maximum'],
            (string)$components['databases'],

            (integer)$components['downloads'],
        
            // assets file
            (string)$components['storage'],

            // download link
            (string)$components['location'],

            // link to repository on github  
            (string)$components['githublink']
        );
    }
}
