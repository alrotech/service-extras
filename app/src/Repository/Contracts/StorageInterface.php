<?php declare(strict_types = 1);

namespace Alroniks\Repository\Contracts;

/**
 * Interface StorageInterface
 * @package Alroniks\Repository\Contracts
 */
interface StorageInterface
{
    /**
     * StorageInterface constructor.
     * @param string $storageKey
     */
    public function __construct(string $storageKey);

    /**
     * @param string $storageKey
     * @return void
     */
    public function setStorageKey(string $storageKey);

    /**
     * Method to persist data
     * Returns new id for just persisted data.
     * @param array $data
     * @return string
     */
    public function persist(array $data) : string;

    /**
     * Returns data by specified id.
     * If there is no such data null is returned.
     * @param string $id
     * @return array
     */
    public function retrieve(string $id) : array;

    /**                   
     * Returns all available entries
     * @return array
     */
    public function all() : array;

    /**
     * @param string $field
     * @param $value
     * @return StorageInterface
     */
    public function search(string $field = '', $value = null) : StorageInterface;

    /**
     * Delete data specified by id
     * If there is no such data - false returns,
     * if data has been successfully deleted - true returns.
     * @param string $id
     * @return bool
     */
    public function delete(string $id) : bool;

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function take(int $limit, int $offset) : array;

    /**
     * @return int
     */
    public function count() : int;

}
