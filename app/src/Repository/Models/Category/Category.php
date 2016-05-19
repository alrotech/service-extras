<?php

namespace Alroniks\Repository\Models\Category;

/**
 * Class Category
 * @package Alroniks\Repository\Models\Category
 */
final class Category
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
}
