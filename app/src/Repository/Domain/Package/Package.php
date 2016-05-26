<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Package;

use Alroniks\Repository\Contracts\EntityInterface;
use DateTime;
use DateTimeImmutable;

/**
 * Class Package
 * @package Alroniks\Repository\Domain\Package
 */
final class Package implements EntityInterface
{
    private $category;

    private $id;
    private $name;
    private $version;
    private $signature;

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

    // count of downloads
    private $downloads;

    // path to file in github assets
    private $storage;

    // link to downloading package
    private $location;

    // link to repository on github
    private $githublink;

    /**
     * Package constructor.
     * @param $category
     * @param $id
     * @param $name
     * @param $version
     * @param $signature
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
     * @param $storage
     * @param $location
     * @param $githublink
     */
    public function __construct(
        $category,
        $id = null,
        $name,
        $version,
        $signature,
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
        $storage,
        $location,
        $githublink
    ) {
        $this->category = $category;
        $this->id = $id;
        $this->name = $name;
        $this->version = $version;
        $this->signature = $signature;
        $this->author = $author;
        $this->license = $license;
        $this->description = $description;
        $this->instructions = $instructions;
        $this->changelog = $changelog;
        $this->createdon = $createdon instanceof DateTimeImmutable ? $createdon : new DateTimeImmutable($createdon);
        $this->editedon = $editedon instanceof DateTimeImmutable ? $editedon : new DateTimeImmutable($editedon);
        $this->releasedon = $releasedon instanceof DateTimeImmutable ? $releasedon : new DateTimeImmutable($releasedon);
        $this->cover = $cover;
        $this->thumb = $thumb;
        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->databases = $databases;
        $this->downloads = $downloads;
        $this->storage = $storage;
        $this->location = $location;
        $this->githublink = $githublink;
    }

    /**
     * @param $uniqueString
     * @return string
     */
    public static function ID($uniqueString)
    {
        return substr(md5(md5($uniqueString)), 0, 10);
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
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
    public function getSignature()
    {
        return $this->signature;
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
     * @return DateTimeImmutable
     */
    public function getCreatedon()
    {
        return $this->createdon;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getEditedon()
    {
        return $this->editedon;
    }

    /**
     * @return DateTimeImmutable
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
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getGitHubLink()
    {
        return $this->githublink;
    }

    public function __toArray()
    {
        $array = get_class_vars(__CLASS__);

        foreach ($array as $key => &$value) {
            $value = call_user_func([$this, 'get' . lcfirst($key)]);
            if ($value instanceof DateTimeImmutable) {
                $value = $value->format(DateTime::ISO8601);
            }
        }

        return $array;
    }

    /**
     * Return object as an array, like hash table
     * @return array
     */
    public function toArray() : array
    {
        // TODO: Implement toArray() method.
    }
}
