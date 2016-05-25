<?php

namespace Alroniks\Repository\Domain\Category;

use Alroniks\Repository\Contracts\EntityInterface;

/**
 * Class Category
 * @package Alroniks\Repository\Domain\Category
 */
final class Category implements EntityInterface
{
    private $repository;
    private $id;
    private $name;

    /**
     * Category constructor.
     * @param $repository
     * @param null $id
     * @param $name
     */
    public function __construct($repository, $id = null, $name)
    {
        $this->repository = $repository;
        $this->id = $id;
        $this->name = $name;
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
    public function getRepository()
    {
        return $this->repository;
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
