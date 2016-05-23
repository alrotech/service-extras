<?php

namespace Alroniks\Repository\Controllers;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\GitHubGateWay;
use Alroniks\Repository\Models\Category\Factory as CategoryFactory;
use Alroniks\Repository\Models\Category\Storage as CategoryStorage;
use Alroniks\Repository\Models\Package\Factory;
use Alroniks\Repository\Models\Package\Package;
use Alroniks\Repository\Models\Package\Storage as PackageStorage;
use Alroniks\Repository\Models\Package\Transformer;
use alroniks\repository\Renderer;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use Slim\Router;


/**
 * Class Package
 * @package alroniks\repository\controllers
 */
class PackageController
{
    /** @var Router */
    private $router;

    /** @var Renderer */
    private $renderer;

    /** @var PackageStorage */
    private $packageStorage;

    /** @var CategoryStorage */
    private $categoryStorage;

    /**
     * Package constructor.
     * @param Router $router
     * @param Renderer $renderer
     * @param PersistenceInterface $persistence
     */
    public function __construct(Router $router, Renderer $renderer, PersistenceInterface $persistence)
    {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->packageStorage = new PackageStorage($persistence, new Factory());
        $this->categoryStorage = new CategoryStorage($persistence, new CategoryFactory());
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return static
     */
    public function search(Request $request, Response $response)
    {
        $query = $request->getParam('query', false);
        $tag = $request->getParam('tag', false);
        $sorter = $request->getParam('sorter', false);

        $start = $request->getParam('start', 0);
        $limit = $request->getParam('limit', 10);

        // filtering by tag
        if ($tag) {
            // todo: replace by universal findBy

            $packages = $this->packageStorage->findByCategory($tag);
        } else {
            $packages = $this->packageStorage->all();
        }

        foreach ($packages as &$package) {
            $package = Transformer::transform($package);
        }

        /** @var Response $response */
        $response = $this->renderer->render($response, [
            'packages' => [
                '@attributes' => [
                    'type' => 'array',
                    'total' => 1,
                    'page' => 1,
                    'of' => 1,
                ],
                'package' => $packages
            ]
        ]);

        return $response->withStatus(200);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     * @throws NotFoundException
     */
    public function download(Request $request, Response $response)
    {
        $packageId = $request->getAttribute('id');

        /** @var Package $package */
        $package = $this->packageStorage->find($packageId);

        if (!$package) {
            throw new NotFoundException($request, $response);
        }

        $asset = $package->getGitHub();

        list($owner, $repository) = explode('/',
            strtolower(str_replace(GitHubGateWay::BASE_URL . '/repos/', '', $asset)));

        $result = GitHubGateWay::api($asset, $owner, $repository, [
            CURLOPT_HTTPHEADER => ['Accept: application/octet-stream'],
            CURLOPT_FOLLOWLOCATION => true
        ]);

        // $result - binary data of zip
        $filename = md5($result);
        $filepath = 'cache/' . $filename;

        if (!file_exists($filepath)) {
            file_put_contents($filepath, $result);
        }

        return $this->router
            ->setBasePath(join('://', [$request->getUri()->getScheme(), $request->getUri()->getAuthority()]))
            ->pathFor('package-file', ['id' => $filename]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return static
     * @throws NotFoundException
     */
    public function file(Request $request, Response $response)
    {
        // доступ к пакету и тд будет проверять через middleware всегда?
        $filename = $request->getAttribute('id');
        $filepath = 'cache/' . $filename;

        if (!file_exists($filepath) || !is_readable($filepath)) {
            throw new NotFoundException($request, $response);
        }

        return $response
            ->withHeader('Content-type', 'application/zip')
            ->withBody(new Stream(fopen($filepath, 'rb')));
    }
}
