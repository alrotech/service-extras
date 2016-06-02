<?php declare(strict_types = 1);

namespace Alroniks\Repository\Persistence;

use Alroniks\Repository\Contracts\StorageInterface;
use Predis\Client;

/**
 * Class Redis
 * @package Alroniks\Repository\Persistence
 */
class Redis implements StorageInterface
{
    private $storageKey;

    private $sequenceKey;
    
    /** @var Client */
    private $client = null;

    /**
     * RedisPersistence constructor.
     * @param string $storageKey
     */
    public function __construct(string $storageKey = '')
    {
        $this->setStorageKey($storageKey);

        $this->client = new Client([
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ]);
    }

    /**
     * @param string $storageKey
     */
    public function setStorageKey(string $storageKey)
    {
        $this->storageKey = $storageKey;
        $this->sequenceKey = $this->storageKey . ':sequence';
    }

    /**
     * @param array $data
     * @return string
     */
    public function persist(array $data) : string
    {
        $key = join(':', [$this->storageKey, $data['id']]);

        $this->client->hmset($key, $data);

        $total = $this->count();
        $this->client->zadd($this->sequenceKey, $total++, $data['id']);

        return $data['id'];
    }

    /**
     * @param $key
     * @return array
     */
    public function retrieve(string $key) : array
    {
        $key = join(':', [$this->storageKey, $key]);

        return $this->client->hgetall($key) ?? [];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id) : bool
    {
        return $this->client->zrem($this->sequenceKey, $id) && $this->client->del($id);
    }

    /**
     * @param string $field
     * @param null $value
     * @return StorageInterface
     */
    public function search(string $field = '', $value = null) : StorageInterface
    {
//        if ($field === '' && is_null($value)) {
//            $this->filtered = $this->data[$this->storageKey];
//
//            return $this;
//        }

//        $this->filtered = array_filter($this->data[$this->storageKey], function ($entity) use ($field, $value) {
//            if (isset($entity[$field]) && $entity[$field] === $value) {
//                return $entity;
//            }
//        });

        return $this;
    }

    /**
     * Returns all available entries
     * @return array
     */
    public function all() : array
    {
        return $this->retrieveCollection($this->client->zrange($this->sequenceKey, 0, -1));
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function take(int $limit, int $offset) : array
    {
        return $this->retrieveCollection($this->client->zrange($this->sequenceKey, $offset, $offset + $limit));
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return $this->client->zcard($this->sequenceKey);
    }

    /**
     * @param array $collection
     * @return array
     */
    private function retrieveCollection(array $collection): array
    {
        foreach ($collection as &$item) {
            $item = $this->retrieve($item);
        }

        return $collection;
    }
}
