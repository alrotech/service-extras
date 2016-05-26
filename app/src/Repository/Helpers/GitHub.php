<?php declare(strict_types = 1);

namespace Alroniks\Repository\Helpers;

/**
 * Class GitHub
 * @package Alroniks\Repository\Helpers
 */
class GitHub
{
    const BASE_URL = 'https://api.github.com';

    /**
     * @param string $url
     * @param string $owner
     * @param string $repository
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function api(string $url, string $owner, string $repository, array $options = []) : array
    {
        $key = getcwd() . "/config/$owner.key";
        if (!file_exists($key) || !is_readable($key)) {
            throw new \Exception("Secret github token for owner $owner not found.");
        }

        $secret = trim(file_get_contents($key));

        $url = str_replace([':owner', ':repo'], [$owner, $repository], $url);
        $url = strpos($url, 'http') !== false ? $url : self::BASE_URL . $url;

        $ch = curl_init();
        curl_setopt_array($ch, array_replace([
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'Alroniks Package Store',
            CURLOPT_HEADER => false,
            CURLOPT_USERPWD => join(':', [$owner, $secret])
        ], $options));

        $result = curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        if (false !== strpos($info, 'amazonaws.com')) {
            return $info;
        }

        return json_decode($result, true);
    }
}
