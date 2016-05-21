<?php

namespace Alroniks\Repository\Models\Package;

use DateTime;

// todo: replace datetime to datetimeimmutable

/**
 * Class Package
 * @package Alroniks\Repository\Models\Package
 */
final class Package
{
    private $id;
    private $name;
    private $version;

    // author and licence
    private $author;
    private $license;

    // full text materials
    private $description;
    private $instructions;
    private $changelog;

    // dates
    private $createdon;
    private $editedon;
    private $releasedon;

    // images (covers)
    private $cover;
    private $thumb;

    // support
    private $minimum;
    private $maximum;
    private $databases;
    
    private $downloads;

    // path to file
    private $package;

    private $categoryId;

    /**
     * Package constructor.
     * @param $categoryId
     * @param $id
     * @param $name
     * @param $version
     * @param $author
     * @param $license
     * @param $description
     * @param $instructions
     * @param $changelog
     * @param $createdon
     * @param $editedon
     * @param $releasedon
     * @param $cover
     * @param $thumb
     * @param $minimum
     * @param $maximum
     * @param $databases    
     * @param $downloads
     * @param $package
     */
    public function __construct(
        $categoryId,
        $id,
        $name,
        $version,
        $author,
        $license,
        $description,
        $instructions,
        $changelog,
        $createdon,
        $editedon,
        $releasedon,
        $cover,
        $thumb,
        $minimum,
        $maximum,
        $databases,
        $downloads,
        $package
    ) {
        $this->categoryId = $categoryId;
        $this->id = $id;
        $this->name = $name;
        $this->version = $version;
        $this->author = $author;
        $this->license = $license;
        $this->description = $description;
        $this->instructions = $instructions;
        $this->changelog = $changelog;
        $this->createdon = $createdon instanceof DateTime ? $createdon : new DateTime($createdon);
        $this->editedon = $editedon instanceof DateTime ? $editedon : new DateTime($editedon);
        $this->releasedon = $releasedon instanceof DateTime ? $releasedon : new DateTime($releasedon);
        $this->cover = $cover;
        $this->thumb = $thumb;
        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->databases = $databases;
        $this->downloads = $downloads;
        $this->package = $package;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @return mixed
     */
    public function getChangelog()
    {
        return $this->changelog;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedon()
    {
        return $this->createdon;
    }

    /**
     * @return \DateTime
     */
    public function getEditedon()
    {
        return $this->editedon;
    }

    /**
     * @return \DateTime
     */
    public function getReleasedon()
    {
        return $this->releasedon;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @return mixed
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * @return mixed
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @return mixed
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * @return mixed
     */
    public function getDatabases()
    {
        return $this->databases;
    }

    /**
     * @return mixed
     */
    public function getDownloads()
    {
        return $this->downloads;
    }

    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }

}
