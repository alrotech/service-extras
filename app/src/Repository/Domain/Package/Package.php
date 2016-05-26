<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Package;

use Alroniks\Repository\Contracts\EntityInterface;

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
     * @param null|EntityInterface $category
     * @param null|string $id
     * @param string $name
     * @param string $version
     * @param string $signature
     * @param string $author
     * @param string $license
     * @param string $description
     * @param string $instructions
     * @param string $changelog
     * @param $createdon
     * @param $editedon
     * @param $releasedon
     * @param string $cover
     * @param string $thumb
     * @param string $minimum
     * @param string $maximum
     * @param string $databases
     * @param int $downloads
     * @param string $storage
     * @param string $location
     * @param string $githublink
     */
    public function __construct(
        $category = null,
        $id = null,
        string $name,
        string $version,
        string $signature,
        string $author,
        string $license,
        string $description,
        string $instructions,
        string $changelog,
        $createdon,
        $editedon,
        $releasedon,
        string $cover,
        string $thumb,
        string $minimum,
        string $maximum,
        string $databases,
        int $downloads,
        string $storage,
        string $location,
        string $githublink
    ) {
        $this->category = $category instanceof EntityInterface ? $category->getId() : $category;
        $this->id = $id ?: substr(md5(md5($this->category . $githublink)), 0, 10);
        $this->name = $name;
        $this->version = $version;
        $this->signature = $signature;
        $this->author = $author;
        $this->license = $license;
        $this->description = $description;
        $this->instructions = $instructions;
        $this->changelog = $changelog;
        $this->createdon = $createdon instanceof \DateTimeImmutable ? $createdon : new \DateTimeImmutable($createdon);
        $this->editedon = $editedon instanceof \DateTimeImmutable ? $editedon : new \DateTimeImmutable($editedon);
        $this->releasedon = $releasedon instanceof \DateTimeImmutable ? $releasedon : new \DateTimeImmutable($releasedon);
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
     * @return mixed
     */
    public function getCategory() : string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion() : string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getSignature() : string
    {
        return $this->signature;
    }

    /**
     * @return string
     */
    public function getAuthor() : string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getLicense() : string
    {
        return $this->license;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getInstructions() : string
    {
        return $this->instructions;
    }

    /**
     * @return string
     */
    public function getChangelog() : string
    {
        return $this->changelog;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedon() : \DateTimeImmutable
    {
        return $this->createdon;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEditedon() : \DateTimeImmutable
    {
        return $this->editedon;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getReleasedon() : \DateTimeImmutable
    {
        return $this->releasedon;
    }

    /**
     * @return string
     */
    public function getCover() : string
    {
        return $this->cover;
    }

    /**
     * @return string
     */
    public function getThumb() : string
    {
        return $this->thumb;
    }

    /**
     * @return string
     */
    public function getMinimum() : string
    {
        return $this->minimum;
    }

    /**
     * @return string
     */
    public function getMaximum() : string
    {
        return $this->maximum;
    }

    /**
     * @return string
     */
    public function getDatabases() : string
    {
        return $this->databases;
    }

    /**
     * @return int
     */
    public function getDownloads() : int
    {
        return $this->downloads;
    }

    /**
     * @return string
     */
    public function getStorage() : string
    {
        return $this->storage;
    }

    /**
     * @return string
     */
    public function getLocation() : string
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getGitHubLink() : string
    {
        return $this->githublink;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $array = get_class_vars(__CLASS__);

        foreach ($array as $key => &$value) {
            $value = call_user_func([$this, 'get' . lcfirst($key)]);
            if ($value instanceof \DateTimeImmutable) {
                $value = $value->format(\DateTime::ISO8601);
            }
        }

        return $array;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getId();
    }
}
