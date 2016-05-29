<?php declare(strict_types = 1);

namespace Alroniks\Repository\Http\Controllers;

use Alroniks\Repository\Contracts\StorageInterface;
use Alroniks\Repository\Domain\Category\Categories;
use Alroniks\Repository\Domain\Category\Category;
use Alroniks\Repository\Domain\Category\CategoryFactory;
use Alroniks\Repository\Domain\Category\CategoryTransformer;
use Alroniks\Repository\Domain\RecordNotFoundException;
use Alroniks\Repository\Domain\Repository\Repositories;
use Alroniks\Repository\Domain\Repository\Repository;
use Alroniks\Repository\Domain\Repository\RepositoryFactory;
use Alroniks\Repository\Domain\Repository\RepositoryTransformer;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;

/**
 * Class Repository
 * @package Alroniks\Repository\Http\Controllers
 */
class RepositoryController
{
    /** @var ContainerInterface */
    private $container;

    /** @var Repositories */
    private $repository;

    /** @var Callable */
    private $renderer;

    /**
     * RepositoryController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        
        /** @var StorageInterface $persistence */
        $persistence = $container->get('persistence');
        $persistence->setStorageKey(Repository::class);
        
        $this->repository = new Repositories($persistence, new RepositoryFactory());
        $this->renderer = $this->container->get('renderer');
    }

    /**
     * @param ServerRequestInterface|Request $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $page = $request->getParam('page', 1);

        list($repositories, $pagination) = $this->repository->paginate($page);

        if (!count($repositories)) {
            throw new NotFoundException($request, $response);
        }

        foreach ($repositories as &$repository) {
            $repository = RepositoryTransformer::transform($repository);
        }

        /** @var ResponseInterface $response */
        $response = call_user_func($this->renderer, $response, [
            'repositories' => [
                '@attributes' => $pagination,
                'repository' => $repositories
            ]
        ]);

        return $response->withStatus(200);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function show(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $repository = $request->getAttribute('id');

        try {
            $repository = $this->repository->find($repository);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException($request, $response);
        }

        /** @var StorageInterface $persistence */
        $persistence = $this->container->get('persistence');
        $persistence->setStorageKey(Category::class);

        $categories = new Categories($persistence, new CategoryFactory());
        $categories = $categories->findBy('repository', $repository->getId());
        
        foreach ($categories as &$category) {
            $category = CategoryTransformer::transform($category);
        }

        /** @var ResponseInterface $response */
        $response = call_user_func($this->renderer, $response, [
            'repository' => array_merge(
                RepositoryTransformer::transform($repository),
                ['tag' => $categories]
            )
        ]);

        return $response->withStatus(200);
    }
}
