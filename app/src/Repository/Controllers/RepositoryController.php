<?php

namespace Alroniks\Repository\Controllers;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Models\Category\Factory as CategoryFactory;
use Alroniks\Repository\Models\Category\Storage as CategoryStorage;
use Alroniks\Repository\Models\Category\Transformer as CategoryTransformer;
use Alroniks\Repository\Models\Repository\Factory as RepositoryFactory;
use Alroniks\Repository\Models\Repository\Storage as RepositoryStorage;
use Alroniks\Repository\Models\Repository\Transformer as RepositoryTransformer;
use Alroniks\Repository\Renderer;
use Slim\Exception\NotFoundException;
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

    /** @var RepositoryStorage */
    private $repositoriesStorage;

    /** @var CategoryStorage */
    private $categoriesStorage;

    /**
     * Repository constructor.
     * @param Renderer $renderer
     * @param PersistenceInterface $persistence
     */
    public function __construct(Renderer $renderer, PersistenceInterface $persistence)
    {
        $this->renderer = $renderer;
        $this->repositoriesStorage = new RepositoryStorage($persistence, new RepositoryFactory());
        $this->categoriesStorage = new CategoryStorage($persistence, new CategoryFactory());
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response)
    {
        $repositories = $this->repositoriesStorage->all();

        foreach ($repositories as &$repository) {
            $repository = RepositoryTransformer::transform($repository);
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

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws NotFoundException
     */
    public function show(Request $request, Response $response)
    {
        $repositoryId = $request->getAttribute('id');
        $repository = $this->repositoriesStorage->findById($repositoryId);

        if (!$repository) {
            throw new NotFoundException($request, $response);
        }

        $categories = $this->categoriesStorage->findByRepositoryId($repositoryId);
        foreach ($categories as &$category) {
            $category = CategoryTransformer::transform($category);
        }

        /** @var Response $response */
        $response = $this->renderer->render($response, [
            'repository' => array_merge(
                RepositoryTransformer::transform($repository),
                ['tag' => $categories]
            )
        ]);

        return $response->withStatus(200);
    }
}
