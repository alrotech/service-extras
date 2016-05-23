<?php

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Models\Category\Category;
use Alroniks\Repository\Models\Category\Factory as CategoryFactory;
use Alroniks\Repository\Models\Category\Storage as CategoryStorage;
use Alroniks\Repository\Models\Package\Package;
use Alroniks\Repository\Models\Repository\Factory as RepositoryFactory;
use Alroniks\Repository\Models\Repository\Repository;
use Alroniks\Repository\Models\Repository\Storage as RepositoryStorage;
use Alroniks\Repository\Models\Package\Factory as PackageFactory;
use Alroniks\Repository\Models\Package\Storage as PackageStorage;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;

/**
 * Class Initializer
 * @package Alroniks\Repository
 */
class Initializer
{
    /** @var Router */
    private $router;

    /** @var PersistenceInterface */
    private $persistence;

    private $config;

    /**
     * Initializer constructor.
     * @param PersistenceInterface $persistence
     * @param $config
     */
    public function __construct(Router $router, PersistenceInterface $persistence, $config)
    {
        $this->router = $router;
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
            
            $repositoryId = Repository::ID($repositoryConfig->name);
            /** @var Repository $repository */
            if (!$repository = $repositoryStorage->find($repositoryId)) {
                $repository = $repositoryStorage->create((new RepositoryFactory())->make([
                    'id' => $repositoryId,
                    'name' => $repositoryConfig->name,
                    'description' => $repositoryConfig->description,
                    'createdon' => 'now',
                    'rank' => $rank,
                    'templated' => $repositoryConfig->templated
                ]));
            }

            if (!isset($repositoryConfig->categories)) {
                continue;
            }
            
            /** @var \stdClass $categoryConfig */
            foreach ($repositoryConfig->categories as $categoryConfig) {
                $categoryId = Category::ID($repositoryId . $categoryConfig->name);
                /** @var Category $category */
                if (!$category = $categoryStorage->find($categoryId)) {
                    $category = $categoryStorage->create((new CategoryFactory())->make([
                        'repositoryId' => $repository->getId(), 
                        'id' => $categoryId, 
                        'name' => $categoryConfig->name
                    ]));    
                }

                if (!isset($categoryConfig->packages)) {
                    continue;
                }

                foreach ($categoryConfig->packages as $packageLink) {
                    $packageId = Package::ID($categoryId . $packageLink);
                    if (!$package = $packageStorage->find($packageId)) {
                        $meta = $this->fetchPackageMeta($packageLink);

                        $downloadLink = $this->router
                            ->setBasePath(join('://', [$request->getUri()->getScheme(), $request->getUri()->getAuthority()]))
                            ->pathFor('package-download', ['id' => $packageId]);

                        $package = $packageStorage->create((new PackageFactory())->make([
                            'categoryId' => $categoryId,
                            'id' => $packageId,
                            'name' => $meta['name'],
                            'version' => $meta['version'],
                            'author' => $meta['author'],
                            'license' => $meta['license'],
                            'description' => $meta['description'],
                            'instructions' => $meta['instructions'],
                            'changelog' => $meta['changelog'],
                            'createdon' => $meta['createdon'],
                            'editedon' => $meta['editedon'],
                            'releasedon' => $meta['releasedon'],
                            'cover' => $meta['cover'],
                            'thumb' => $meta['thumb'],
                            'minimum' => $meta['minimum'],
                            'maximum' => $meta['maximum'],
                            'databases' => $meta['databases'],
                            'downloads' => $meta['downloads'],
                            'package' => $downloadLink,
                            'github' => $packageLink
                        ]));
                    }
                }
            }
            $rank++;
        }

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

        $downloads = isset($release['assets'][0]) && $release['assets'][0]['download_count']
            ? $release['assets'][0]['download_count']
            : 0;

        return [
            'name' => $packageMeta->name,
            'version' => $release['tag_name'],
            'author' => $packageMeta->author,
            'license' => $packageMeta->license,
            'description' => $packageMeta->description,
            'instructions' => file_get_contents($instructions['download_url']),
            'changelog' => file_get_contents($changeLog['download_url']),
            'createdon' => $release['created_at'],
            'editedon' => $release['created_at'],
            'releasedon' => $release['published_at'],
            'cover' => $packageMeta->screenshot,
            'thumb' => $packageMeta->thumbnail,
            'minimum' => $packageMeta->support->modx,
            'maximum' => 10000000,
            'databases' => join(', ', $packageMeta->support->db),
            'downloads' => $downloads,
            'file' => ''
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
