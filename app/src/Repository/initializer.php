<?php

namespace Alroniks\Repository;

use Alroniks\Repository\Contracts\PersistenceInterface;
use Alroniks\Repository\Models\Repository\Factory;
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
        $repositoryStorage = new RepositoryStorage($this->persistence, new Factory());
        
        $rank = 0;
        foreach ($this->config->repositories as $repository) {
            $repositoryStorage->add(new Repository(
                null,
                $repository->name,
                $repository->description,
                'now',
                $rank,
                $repository->templated
            ));
            $rank++;
        }

        $response = $next($request, $response);

        return $response;
    }
    
    private function loadRepository($repository)
    {
        
    }


}
// список репозиториев
// список категорий + 1 динамическая общаяя
// список пакетов (+meta фетчер)


