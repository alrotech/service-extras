<?php

namespace Alroniks\Repository\Controllers;

use Alroniks\Repository\Models\Repository\Repository;
use Alroniks\Repository\Models\Repository\Storage;
use Alroniks\Repository\Models\Repository\Transformer;
use Alroniks\Repository\Renderer;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Repository
 * @package Alroniks\Repository\Controllers
 */
class RepositoryController
{
    /** @var Renderer */
    private $renderer;

    /** @var Storage */
    private $storage;

    /**
     * Repository constructor.
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
        $this->storage = new Storage();

        $this->storage->add(new Repository(null, 'Packages for site', 'Special delivered packages for this site', 'now',
            0, 0));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function index(Request $request, Response $response)
    {
        $repositories = $this->storage->findAll();

        foreach ($repositories as &$repository) {
            $repository = Transformer::transform($repository);
        }

        /** @var Response $response */
        $response = $this->renderer->render($response, [
            'repositories' => [
                '@attributes' => [
                    'type' => 'array',
                    'total' => count($repositories),
                    'page' => 1, // todo: need calculate it
                    'of' => 1,
                ],
                'repository' => $repositories
            ]
        ]);

        return $response->withStatus(200);
    }

    public function show(Request $request, Response $response)
    {
        $repositoryId = $request->getAttribute('id');

        $repository = $this->storage->findById($repositoryId);

        //$cStorage = 

        $categories =


            //print_r($repository);


            /** @var Response $response */
        $response = $this->renderer->render($response, [
            'repository' => Transformer::transform($repository)
        ]);

        return $response->withStatus(200);
    }
}
