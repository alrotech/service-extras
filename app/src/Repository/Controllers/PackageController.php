<?php

namespace Alroniks\Repository\Controllers;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Models\Category\Storage as CategoryStorage;
use Alroniks\Repository\Models\Category\Factory as CategoryFactory;
use Alroniks\Repository\Models\Package\Factory;
use Alroniks\Repository\Models\Package\Storage as PackageStorage;
use Alroniks\Repository\Models\Package\Transformer;
use alroniks\repository\Renderer;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Package
 * @package alroniks\repository\controllers
 */
class PackageController
{
    /** @var Renderer  */
    private $renderer;

    /** @var PackageStorage */
    private $packageStorage;

    /** @var CategoryStorage  */
    private $categoryStorage;

    /**
     * Package constructor.
     * @param Renderer $renderer
     * @param PersistenceInterface $persistence
     */
    public function __construct(Renderer $renderer, PersistenceInterface $persistence)
    {
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
     * @throws NotFoundException
     */
    public function download(Request $request, Response $response)
    {
        $packageId = $request->getAttribute('id');

        $package = $this->packageStorage->find($packageId);

        if (!$package) {
            throw new NotFoundException($request, $response);
        }

        //

        echo 1;
    }
}
