<?php declare(strict_types = 1);

namespace Alroniks\Repository\Http\Controllers;

use Alroniks\Repository\Contracts\StorageInterface;
use Alroniks\Repository\Domain\Repository\Factory;
use Alroniks\Repository\Domain\Repository\Repositories;
use Alroniks\Repository\Domain\Repository\Repository;
use Alroniks\Repository\Helpers\Renderer;
use Alroniks\Repository\Domain\Repository\Transformer;
use Alroniks\Repository\Models\Category\Storage;
use Alroniks\Repository\Persistence\Memory;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class TestController
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function test(Request $request, Response $response)
    {

        $repository_1 = new Repository(null, 'Repo Test 1', 'D1', '', 0, false);
        $repository_2 = new Repository(null, 'Repo Test 2', 'D2', '', 0, true);
        
        $persistence = new Memory(Repository::class);
        $factory = new Factory();
        
        $list = new Repositories($persistence, $factory);

        $list->add($repository_1);
        $list->add($repository_2);

        $all = $list->findAll();

        print_r($all);

        $special = $list->find('86110a5e6f');

        //print_r($special);
        
        // delete
        var_dump($list->remove($special));

        print_r($all = $list->findAll());

        return $response;
    }
}
