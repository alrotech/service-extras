<?php

namespace Alroniks\Repository\Models\Category;

use Alroniks\Repository\Contracts\DomainObjectInterface;

/**
 * Class Category
 * @package Alroniks\Repository\Models\Category
 */
final class Category implements DomainObjectInterface
{
    private $id;
    private $name;
    private $repositoryId;

    /**
     * Category constructor.
     * @param $repositoryId
     * @param null $id
     * @param $name
     */
    public function __construct($repositoryId, $id = null, $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->repositoryId = $repositoryId;
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
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getRepositoryId()
    {
        return $this->repositoryId;
    }

    /**
     * @param $field
     * @param $value
     */
    public function __set($field, $value) {}

    public function __toArray()
    {
        $array = get_class_vars(__CLASS__);

        foreach ($array as $key => &$value) {
            $value = call_user_func([$this, 'get' . lcfirst($key)]);
        }

        return $array;
    }
}
