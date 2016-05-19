<?php

namespace Alroniks\Repository\Models\Package;

use DateTime;

/**
 * Class Transformer
 * @package Alroniks\Repository\Models\Package
 */
class Transformer
{
    /**
     * @param Package $package
     * @return array
     */
    public static function transform(Package $package)
    {
        $signature = join('-', [$package->getName(), $package->getVersion(), 'pl']);

        return [
            'id' => $package->getId(),
            'name' => $package->getName(),
            'version' => $package->getVersion(),
            'release' => 'pl',
            'display_name' => $signature,
            'signature' => $signature,
            'author' => $package->getAuthor(),
            'license' => $package->getLicense(),
            'description' => ['@cdata' => $package->getDescription()],
            'instructions' => ['@cdata' => $package->getInstructions()],
            'changelog' => ['@cdata' => $package->getChangelog()],
            'createdon' => $package->getCreatedon()->format(DateTime::ISO8601),
            'editedon' => $package->getEditedon()->format(DateTime::ISO8601),
            'releasedon' => $package->getReleasedon()->format(DateTime::ISO8601),
            'screenshot' => $package->getCover(),
            'thumbnail' => $package->getThumb(),
            'minimum_supports' => $package->getMinimum(),
            'breaks_at' => $package->getMaximum() ?: 1000000,
            'supports_db' => $package->getDatabases(),
            'location' => $package->getPackage(),
            'downloads' => 11, // generic
        ];
    }
}
