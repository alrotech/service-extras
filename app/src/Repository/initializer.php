<?php

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Models\Category\Category;
use Alroniks\Repository\Models\Category\Factory as CategoryFactory;
use Alroniks\Repository\Models\Category\Storage as CategoryStorage;
use Alroniks\Repository\Models\Repository\Factory as RepositoryFactory;
use Alroniks\Repository\Models\Repository\Repository;
use Alroniks\Repository\Models\Repository\Storage as RepositoryStorage;
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

        $rank = 0;
        /** @var \stdClass $repositoryConfig */
        foreach ($this->config->repositories as $repositoryConfig) {

            // not show this repository if domain are not allowed
            if (!empty($repositoryConfig->domains)) {
                if (!in_array($request->getServerParams()['HTTP_HOST'], $repositoryConfig->domains)) {
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
            }

            $rank++;
        }

        $response = $next($request, $response);

        return $response;
    }

}
