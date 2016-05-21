<?php

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Predis\Client;

/**
 * Class RedisPersistence
 * @package Alroniks\Repository
 */
class RedisPersistence implements PersistenceInterface
{
    /** @var Client */
    private $client = null;

    /**
     * RedisPersistence constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ]);
    }

    /**
     * @param $key
     * @return array
     */
    public function retrieve($key)
    {
        return $this->client->hgetall($key);
    }

    /**
     * @param $key
     * @return int
     */
    public function purge($key)
    {
        return $this->client->del($key);
    }

    /**
     * @param $key
     * @param $data
     * @param int $ttl
     * @return mixed
     */
    public function persist($key, $data, $ttl = 0)
    {
        $this->client->hmset($key, $data);
        if ($ttl) {
            $this->client->expire($key, $ttl);
        }
    }

    /**
     * @param $key
     * @return string
     */
    public function collection($key)
    {
        $keys = $this->client->keys($key);
        foreach ($keys as &$k) {
            $k = $this->client->hgetall($k);
        }

        return $keys;
    }

    /**
     * @param $key
     * @return int
     */
    public function exists($key)
    {
        return $this->client->exists($key);
    }
}
