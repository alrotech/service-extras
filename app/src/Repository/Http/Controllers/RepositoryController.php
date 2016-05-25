<?php declare(strict_types = 1);

namespace Alroniks\Repository\Http\Controllers;

use Alroniks\Repository\Contracts\StorageInterface;
use Alroniks\Repository\Domain\RecordNotFoundException;
use Alroniks\Repository\Domain\Repository\Factory;
use Alroniks\Repository\Domain\Repository\Repositories;
use Alroniks\Repository\Domain\Repository\Repository;
use Alroniks\Repository\Domain\Repository\Transformer;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\NotFoundException;

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
        
        $this->repository = new Repositories($persistence, new Factory());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $repositories = $this->repository->findAll();

        foreach ($repositories as &$repository) {
            $repository = Transformer::transform($repository);
        }

        /** @var ResponseInterface $response */
        $response = $this->container->get('renderer')->render($response, [
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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function show(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $repository_1 = new Repository(null, 'Repo Test 1', 'D1', '', 0, false);
        $repository_2 = new Repository(null, 'Repo Test 2', 'D2', '', 0, true);

        $this->repository->add($repository_1);
        $this->repository->add($repository_2);

        $repository = $request->getAttribute('id');

        try {
            $repository = $this->repository->find($repository);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException($request, $response);
        }

        // need to fix
        //$categories = $this->categoriesStorage->findByRepositoryId($repositoryId);
        //foreach ($categories as &$category) {
            //$category = CategoryTransformer::transform($category);
        //}

        /** @var ResponseInterface $response */
        $response = $this->container->get('renderer')->render($response, [
            'repository' => array_merge(
                Transformer::transform($repository),
                //['tag' => $categories]
                ['tag' => []]
            )
        ]);

        return $response->withStatus(200);
    }
}
