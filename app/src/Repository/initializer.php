<?php

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Models\Category\Category;
use Alroniks\Repository\Models\Category\Factory as CategoryFactory;
use Alroniks\Repository\Models\Category\Storage as CategoryStorage;
use Alroniks\Repository\Models\Repository\Factory as RepositoryFactory;
use Alroniks\Repository\Models\Repository\Repository;
use Alroniks\Repository\Models\Repository\Storage as RepositoryStorage;
use Alroniks\Repository\Models\Package\Factory as PackageFactory;
use Alroniks\Repository\Models\Package\Storage as PackageStorage;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Initializer
 * @package Alroniks\Repository
 */
class Initializer
{
    /** @var PersistenceInterface */
    private $persistence;

    private $config;

    /**
     * Initializer constructor.
     * @param PersistenceInterface $persistence
     * @param $config
     */
    public function __construct(PersistenceInterface $persistence, $config)
    {
        $this->persistence = $persistence;
        $this->config = json_decode(file_get_contents($config));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $repositoryStorage = new RepositoryStorage($this->persistence, new RepositoryFactory());
        $categoryStorage = new CategoryStorage($this->persistence, new CategoryFactory());
        $packageStorage = new PackageStorage($this->persistence, new PackageFactory());

        $rank = 0;
        /** @var \stdClass $repositoryConfig */
        foreach ($this->config->repositories as $repositoryConfig) {

            // not show this repository if domain are not allowed
            if (!empty($repositoryConfig->domains)) {
                if (!in_array($request->getParam('http_host'), $repositoryConfig->domains)) {
                    continue;
                }
            }

            /** @var Repository $repository */
            $repository = (new RepositoryFactory())->make([
                null, $repositoryConfig->name, $repositoryConfig->description,
                'now', $rank, $repositoryConfig->templated
            ]);
            $repositoryStorage->add($repository);

            if (!isset($repositoryConfig->categories)) {
                continue;
            }

            /** @var \stdClass $categoryConfig */
            foreach ($repositoryConfig->categories as $categoryConfig) {
                /** @var Category $category */
                $category = (new CategoryFactory())->make([
                    $repository->getId(), null, $categoryConfig->name
                ]);
                $categoryStorage->add($category);
                
                if (!isset($categoryConfig->packages)) {
                    continue;
                }
                
                foreach ($categoryConfig->packages as $packageLink) {
                    $package = (new PackageFactory())->make(array_merge([$category->getId(), null], $this->fetchPackageMeta($packageLink)));
                    $packageStorage->add($package);
                }
            }

            $rank++;
        }

        //print_r($this->persistence);

        $response = $next($request, $response);

        return $response;
    }

    private function fetchPackageMeta($url)
    {
        list($owner, $repository) = explode('/', strtolower(str_replace('https://github.com/', '', $url)));

        $package = $this->request('/repos/:owner/:repo/contents/transport.json', $owner, $repository);
        $packageMeta = json_decode(file_get_contents($package['download_url']));

        $instructions = $this->request('/repos/:owner/:repo/contents/meta/readme.txt', $owner, $repository);
        $changeLog = $this->request('/repos/:owner/:repo/contents/meta/changelog.txt', $owner, $repository);

        $release = $this->request('/repos/:owner/:repo/releases/latest', $owner, $repository);

        $file = isset($release['assets'][0]) && $release['assets'][0]['browser_download_url']
            ? $release['assets'][0]['browser_download_url']
            : '';

        $downloads = isset($release['assets'][0]) && $release['assets'][0]['download_count']
            ? $release['assets'][0]['download_count']
            : 0;

        return [
            $packageMeta->name,
            $release['tag_name'],
            $packageMeta->author,
            $packageMeta->license,
            $packageMeta->description,
            file_get_contents($instructions['download_url']),
            file_get_contents($changeLog['download_url']),
            $release['created_at'],
            $release['created_at'],
            $release['published_at'],
            $packageMeta->screenshot,
            $packageMeta->thumbnail,
            $packageMeta->support->modx,
            10000000,
            join(', ', $packageMeta->support->db),
            $downloads,
            $file
        ];
    }

    private function request($url, $owner, $repository)
    {
        $baseUrl = 'https://api.github.com';
        $secret = trim(file_get_contents("config/$owner.key"));

        $url = str_replace([':owner', ':repo'], [$owner, $repository], $url);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => strpos($url, 'http') !== false ? $url : $baseUrl . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'Alroniks Package Store',
            CURLOPT_HEADER => false,
            CURLOPT_USERPWD => join(':', [$owner, $secret])
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
