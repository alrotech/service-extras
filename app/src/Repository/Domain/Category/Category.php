<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Category;

use Alroniks\Repository\Contracts\EntityInterface;
use Alroniks\Repository\Helpers\Originality;

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
     * @param string|null $repository
     * @param string|null $id
     * @param string $name
     */
    public function __construct(
        $repository = null,
        string $id = null,
        string $name
    ) {
        $this->repository = $repository instanceof EntityInterface ? $repository->getId() : $repository;
        $this->id = $id ?: call_user_func(new Originality, $this->repository . $name);
        $this->name = $name;
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
    public function getRepository() : string
    {
        return $this->repository;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $array = get_class_vars(__CLASS__);

        foreach ($array as $key => &$value) {
            $value = call_user_func([$this, 'get' . lcfirst($key)]);
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
