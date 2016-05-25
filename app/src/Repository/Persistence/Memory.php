<?php declare(strict_types = 1);

namespace Alroniks\Repository\Persistence;

use Alroniks\Repository\Contracts\StorageInterface;

/**
 * Memory storage implementation
 * @package Alroniks\Repository\Persistence
 */
class Memory implements StorageInterface
{
    private $storageKey;

    /** @var array */
    private $data = [];

    public function __construct(string $storageKey = '')
    {
        $this->storageKey = $storageKey;
    }

    /**
     * @param string $storageKey
     */
    public function setStorageKey(string $storageKey)
    {
        $this->storageKey = $storageKey;
    }

    /**
     * Method to persist data
     * Returns new id for just persisted data.
     * @param array $data
     * @return string
     */
    public function persist(array $data) : string
    {
        $this->data[$this->storageKey][$data['id']] = $data;

        return $data['id'];
    }

    /**
     * @param $key
     * @return array
     */
    public function retrieve(string $key) : array
    {
        return $this->data[$this->storageKey][$key] ?? [];
    }

    /**
     * @return array
     */
    public function all() : array
    {
        return $this->data[$this->storageKey] ?? [];
    }

    /**
     * Delete data specified by key
     * If there is no such data - false returns,
     * if data has been successfully deleted - true returns.
     * @param string $key
     * @return bool
     */
    public function delete(string $key) : bool
    {
        if (isset($this->data[$this->storageKey][$key])) {
            unset($this->data[$this->storageKey][$key]);

            return empty(isset($this->data[$this->storageKey][$key]));
        }

        return false;
    }
}
