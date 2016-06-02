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
    private $config = [
        'key.storage' => '',
        'key.sequence' => '',
        'fields' => []
    ];
    
    /** @var Client */
    private $client = null;

    /**
     * RedisPersistence constructor.
     * @param array $config
     * @internal param string $key.storage
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);

        $this->client = new Client([
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ]);
    }

    /**
     * @param array $config
     * @internal param string $key.storage
     */
    public function setConfig(array $config)
    {
        if (!empty($config['key.storage'])) {
            $config['key.sequence'] = $config['key.sequence']
                ?? $config['key.storage'] . ':sequence';
        }

        $this->config = array_merge($this->config, $config);
    }

    /**
     * @param array $data
     * @return string
     */
    public function persist(array $data) : string
    {
        $key = join(':', [$this->config['key.storage'], $data['id']]);

        $this->client->hmset($key, $data);

        $additional = array_intersect($this->config['fields'], array_keys($data));
        foreach ($additional as $field) {
            $value = $data[$field];
            $k = join(':', [$field, $value]);
            $this->client->zadd($k, 0, $data['id']);
        }

        $total = $this->count();
        $rank = $data['rank'] ?? $total++;
        $this->client->zadd($this->config['key.sequence'], $rank, $data['id']);

        return $data['id'];
    }

    /**
     * @param $key
     * @return array
     */
    public function retrieve(string $key) : array
    {
        $key = join(':', [$this->config['key.storage'], $key]);

        return $this->client->hgetall($key) ?? [];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id) : bool
    {
        return $this->client->zrem($this->config['key.sequence'], $id) && $this->client->del($id);
    }

    /**
     * @param string $field
     * @param null $value
     * @return StorageInterface
     */
    public function search(string $field = '', $value = null) : StorageInterface
    {
//        if ($field === '' && is_null($value)) {
//            $this->filtered = $this->data[$this->key.storage];
//
//            return $this;
//        }

//        $this->filtered = array_filter($this->data[$this->key.storage], function ($entity) use ($field, $value) {
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
        return $this->retrieveCollection($this->client->zrange($this->config['key.sequence'], 0, -1));
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function take(int $limit, int $offset) : array
    {
        return $this->retrieveCollection($this->client->zrange($this->config['key.sequence'], $offset, $offset + $limit));
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return $this->client->zcard($this->config['key.sequence']);
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
