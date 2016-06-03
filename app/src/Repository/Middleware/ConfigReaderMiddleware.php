<?php declare(strict_types = 1);

namespace Alroniks\Repository\Middleware;

use Alroniks\Repository\Contracts\StorageInterface;
use Alroniks\Repository\Domain\Category\Categories;
use Alroniks\Repository\Domain\Category\CategoryFactory;
use Alroniks\Repository\Domain\Package\PackageFactory;
use Alroniks\Repository\Domain\Package\Packages;
use Alroniks\Repository\Domain\RecordNotFoundException;
use Alroniks\Repository\Domain\Repository\Repositories;
use Alroniks\Repository\Domain\Repository\RepositoryFactory;
use Alroniks\Repository\Helpers\GitHub;
use Alroniks\Repository\Helpers\Originality;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;

class ConfigReaderMiddleware
{
    /** @var ContainerInterface */
    private $container;

    /** @var StorageInterface */
    private $persistence;

    /** @var array */
    private $config;
    
    public function __construct(ContainerInterface $container, string $config)
    {
        $this->container = $container;
        $this->persistence = $container->get('persistence');
        $this->config = json_decode(file_get_contents($config));
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) : ResponseInterface
    {
        /** @var Repositories $repositories */
        $repositories = new Repositories($this->persistence, new RepositoryFactory());

        /** @var Categories $categories */
        $categories = new Categories($this->persistence, new CategoryFactory());

        /** @var Packages $packages */
        $packages = new Packages($this->persistence, new PackageFactory());

        $rank = 0;
        /** @var \stdClass $repositoryConfig */
        foreach ($this->config->repositories as $repositoryConfig) {

            $repositoryId = call_user_func(new Originality, $repositoryConfig->name);

            try {
                $repository = $repositories->find($repositoryId);
            } catch (RecordNotFoundException $e) {
                $repository = $repositories->add((new RepositoryFactory())->make([
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

                $categoryId = call_user_func(new Originality, $repositoryId . $categoryConfig->name);

                try {
                    $category = $categories->find($categoryId);
                } catch (RecordNotFoundException $e) {
                    $category = $categories->add((new CategoryFactory())->make([
                        'repository' => $repository,
                        'id' => $categoryId,
                        'name' => $categoryConfig->name
                    ]));
                }

                if (!isset($categoryConfig->packages)) {
                    continue;
                }

                foreach ($categoryConfig->packages as $packageLink) {

                    $packageId = call_user_func(new Originality, $categoryId . $packageLink);

                    try {
                        $packages->find($packageId);
                    } catch (RecordNotFoundException $e) {
                        $meta = $this->fetchPackageMeta($packageLink);

                        $location = $this->container->get('router')
                            ->setBasePath(join('://', [$request->getUri()->getScheme(), $request->getUri()->getAuthority()]))
                            ->pathFor('package-download', ['id' => $packageId]);

                        $packages->add((new PackageFactory())->make([
                            'category' => $category,
                            'id' => $packageId,
                            'name' => $meta['name'],
                            'version' => $meta['version'],
                            'signature' => join('-', [$meta['name'], $meta['version'], 'pl']),
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
                            'storage' => $meta['storage'],
                            'location' => $location,
                            'githublink' => $packageLink
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

        $package = GitHub::api('/repos/:owner/:repo/contents/transport.json', $owner, $repository);
        $packageMeta = json_decode(file_get_contents($package['download_url']));

        $instructions = GitHub::api('/repos/:owner/:repo/contents/meta/readme.txt', $owner, $repository);
        $changeLog = GitHub::api('/repos/:owner/:repo/contents/meta/changelog.txt', $owner, $repository);
        $cover = GitHub::api('/repos/:owner/:repo/contents/meta/cover.png', $owner, $repository);
        $thumb = GitHub::api('/repos/:owner/:repo/contents/meta/thumb.png', $owner, $repository);

        $release = GitHub::api('/repos/:owner/:repo/releases/latest', $owner, $repository);

        $storage = isset($release['assets'][0]) && $release['assets'][0]['url']
            ? $release['assets'][0]['url']
            : '';

        $downloads = isset($release['assets'][0]) && $release['assets'][0]['download_count']
            ? $release['assets'][0]['download_count']
            : 0;

        return [
            'name' => $packageMeta->name,
            'version' => $release['tag_name'] ?? '',
            'author' => $packageMeta->author,
            'license' => $packageMeta->license,
            'description' => $packageMeta->description,
            'instructions' => isset($instructions['download_url']) ? file_get_contents($instructions['download_url']) : '',
            'changelog' => isset($changeLog['download_url']) ? file_get_contents($changeLog['download_url']) : '',
            'createdon' => $release['created_at'] ?? '',
            'editedon' => $release['created_at'] ?? '',
            'releasedon' => $release['published_at'] ?? '',
            'cover' => $cover['download_url'] ?? '',
            'thumb' => $thumb['download_url'] ?? '',
            'minimum' => $packageMeta->support->modx,
            'maximum' => 10000000,
            'databases' => join(', ', $packageMeta->support->db),
            'downloads' => $downloads,
            'storage' => $storage
        ];
    }
}
