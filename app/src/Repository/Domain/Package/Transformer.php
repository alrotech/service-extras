<?php

namespace Alroniks\Repository\Domain\Package;

use Alroniks\Repository\Contracts\EntityInterface;
use DateTime;

/**
 * Class Transformer
 * @package Alroniks\Repository\Domain\Package
 */
class Transformer
{
    /**
     * @param EntityInterface $entity
     * @return array
     */
    public static function transform(EntityInterface $entity) : array
    {
        $output = $entity->toArray();

        $output['release'] = 'pl';
        $output['description'] = ['@cdata' => $package->getDescription()];
        $output['instructions'] = ['@cdata' => $package->getInstructions()];
        $output['changelog'] = ['@cdata' => $package->getChangelog()];

        $output['breaks_at'] = $output['changelog'] ?: 1000000;

        return $output;

//        return [
//            'createdon' => $package->getCreatedon()->format(DateTime::ISO8601),
//            'editedon' => $package->getEditedon()->format(DateTime::ISO8601),
//            'releasedon' => $package->getReleasedon()->format(DateTime::ISO8601),
//        ];
    }
}
