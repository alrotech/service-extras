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
     * @param string $field
     * @param $value
     * @return array
     */
    public function search(string $field, $value) : array
    {
        return array_filter($this->data[$this->storageKey], function ($entity) use ($field, $value) {
            if ($entity[$field] === $value) {
                return $entity;
            }
        });
    }

    /**
     * Delete data specified by id
     * If there is no such data - false returns,
     * if data has been successfully deleted - true returns.
     * @param string $id
     * @return bool
     */
    public function delete(string $id) : bool
    {
        if (isset($this->data[$this->storageKey][$id])) {
            unset($this->data[$this->storageKey][$id]);

            return empty(isset($this->data[$this->storageKey][$id]));
        }

        return false;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function take(int $limit, int $offset) : array
    {
        return array_slice($this->data[$this->storageKey], $offset, $limit, true);
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->data[$this->storageKey]);
    }
}
