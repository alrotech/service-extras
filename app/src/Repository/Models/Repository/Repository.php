<?php

namespace Alroniks\Repository\Models\Repository;

use DateTime;

/**
 * Class Repository
 * @package Alroniks\Repository\Models\Repository
 */
final class Repository
{
    private $id;
    private $name;
    private $description;
    private $createdon;
    private $rank;
    private $templated;

    /**
     * Repository constructor.
     * @param null $id
     * @param $name
     * @param $description
     * @param $createdon
     * @param $rank
     * @param $templated
     */
    public function __construct($id = null, $name, $description, $createdon, $rank, $templated)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdon = $createdon;
        $this->rank = $rank;
        $this->templated = $templated;
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdon;
    }

    /**
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @return boolean
     */
    public function getTemplated()
    {
        return $this->templated;
    }

    /**
     * @param $field
     * @param $value
     */
    public function __set($field, $value)
    {
    }
}
