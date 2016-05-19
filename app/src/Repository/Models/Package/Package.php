<?php

namespace alroniks\repository\models;

class Package
{
    private $id;
    private $version;


}


$results[] = array(
    'id' => (string)$package->id,
    'version' => (string)$package->version,
    'release' => (string)$package->release,
    'signature' => (string)$package->signature,
    'author' => (string)$package->author,
    'description' => (string)$package->description,
    'instructions' => (string)$package->instructions,
    'changelog' => (string)$package->changelog,
    'createdon' => (string)$package->createdon,
    'editedon' => (string)$package->editedon,
    'name' => (string)$package->name,
    'downloads' => number_format((integer)$package->downloads, 0),
    'releasedon' => $releasedon,
    'screenshot' => (string)$package->screenshot,
    'thumbnail' => !empty($package->thumbnail) ? (string)$package->thumbnail : (string)$package->screenshot,
    'license' => (string)$package->license,
    'minimum_supports' => (string)$package->minimum_supports,
    'breaks_at' => (integer)$package->breaks_at != 10000000 ? (string)$package->breaks_at : '',
    'supports_db' => (string)$package->supports_db,
    'location' => (string)$package->location,
    'version-compiled' => $versionCompiled,
    'downloaded' => !empty($installed) ? true : false,
    'featured' => (boolean)$package->featured,
    'audited' => (boolean)$package->audited,
    'dlaction-icon' => $installed ? 'package-installed' : 'package-download',
    'dlaction-text' => $installed ? $this->xpdo->lexicon('downloaded') : $this->xpdo->lexicon('download'),
);
