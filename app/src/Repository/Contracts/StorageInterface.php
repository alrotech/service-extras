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
     * @param int $id
     * @return array
     */
    public function retrieve(string $id) : array;

    /**                   
     * Returns all available entries
     * @return array
     */
    public function all() : array;

    /**
     * Delete data specified by id
     * If there is no such data - false returns,
     * if data has been successfully deleted - true returns.
     * @param string $id
     * @return bool
     */
    public function delete(string $id) : bool;

}
