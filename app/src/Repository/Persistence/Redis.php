<?php declare(strict_types = 1);

namespace Alroniks\Repository\Domain\Persistence;

use Alroniks\Repository\Contracts\StorageInterface;
use Predis\Client;


// auto check which key use base on class

/**
 * Class Redis
 * @package Alroniks\Repository\Persistence
 */
class Redis implements StorageInterface
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
     * @return boolean
     */
//    public function persist($key, $data, $ttl = 0)
//    {
//        if ($this->client->hmset($key, $data)) {
//            if ($ttl) {
//                $this->client->expire($key, $ttl);
//            }
//
//            return true;
//        }
//
//        return false;
//    }

    public function persist($data)
    {
        // TODO: Implement persist() method.
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

    /**
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        // TODO: Implement delete() method.
    }
}
