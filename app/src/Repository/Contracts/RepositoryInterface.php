<?php

namespace Alroniks\Repository\Contracts;

/**
 * Interface RepositoryInterface
 * @package Alroniks\Repository\Contracts
 */
interface RepositoryInterface
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    //public function paginate($perPage = 10, $columns = ['*']);

    /**
     * @param DomainObjectInterface $object
     */
    public function create(DomainObjectInterface $object);

    /**
     * @param DomainObjectInterface $object
     * @param $id
     * @return mixed
     */
    public function update(DomainObjectInterface $object, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = []);
}
