<?php

namespace Alroniks\Repository\Controllers;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Models\Package\Factory;
use Alroniks\Repository\Models\Package\Package;
use Alroniks\Repository\Models\Package\Storage;
use Alroniks\Repository\Models\Package\Transformer;
use alroniks\repository\Renderer;
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

    /** @var Storage */
    private $storage;

    /**
     * Package constructor.
     * @param Renderer $renderer
     * @param PersistenceInterface $persistence
     */
    public function __construct(Renderer $renderer, PersistenceInterface $persistence)
    {
        $this->renderer = $renderer;
        $this->storage = new Storage($persistence, new Factory());
    }

    public function search(Request $request, Response $response)
    {
        /*
        'query' => false,
        'tag' => false,
        'sorter' => false,
        'start' => 0,
        'limit' => 10,
        'dateFormat' => '%b %d, %Y',
        'supportsSeparator' => ', ',
         */

        $packages = $this->storage->findAll();

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
}
