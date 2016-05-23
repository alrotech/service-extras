<?php

namespace Alroniks\Repository;

/**
 * Class GitHubGateWay
 * @package Alroniks\Repository
 */
class GitHubGateWay
{
    const BASE_URL = 'https://api.github.com';

    /**
     * @param $url
     * @param $owner
     * @param $repository
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public static function api($url, $owner, $repository, $options = [])
    {
        $key = getcwd() . "/config/$owner.key";
        if (!file_exists($key) || !is_readable($key)) {
            throw new \Exception("Secret github token for owner $owner not found.");
        }

        $secret = trim(file_get_contents($key));

        $url = str_replace([':owner', ':repo'], [$owner, $repository], $url);
        $url = strpos($url, 'http') !== false ? $url : self::BASE_URL . $url;

        $config = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'Alroniks Package Store',
            CURLOPT_HEADER => false,
            CURLOPT_USERPWD => join(':', [$owner, $secret])
        ];

        if ($options) {
            $config = array_replace($config, $options);
        }

        $ch = curl_init();
        curl_setopt_array($ch, $config);
        $result = curl_exec($ch);
        curl_close($ch);
        
        if (strpos($result, "\0") !== false) {
            return $result;
        }

        return json_decode($result, true);
    }
}
