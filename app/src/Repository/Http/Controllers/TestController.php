<?php declare(strict_types = 1);

namespace Alroniks\Repository\Http\Controllers;

use Alroniks\Repository\Contracts\StorageInterface;

use Alroniks\Repository\Domain\Category\Category;
use Alroniks\Repository\Domain\Category\Categories; // repository
use Alroniks\Repository\Domain\Category\CategoryFactory;

use Alroniks\Repository\Domain\Package\Package;
use Alroniks\Repository\Domain\Package\PackageFactory;
use Alroniks\Repository\Domain\Package\Packages;
use Alroniks\Repository\Domain\Repository\RepositoryFactory;
use Alroniks\Repository\Domain\Repository\Repositories;
use Alroniks\Repository\Domain\Repository\Repository;
use Alroniks\Repository\Helpers\Renderer;
use Alroniks\Repository\Domain\Repository\RepositoryTransformer;
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
        /** @var StorageInterface $persistence */
        $persistence = $this->container->get('persistence');

        $persistence->setStorageKey(Repository::class);
        $repository = new Repository(null, 'Repo Test 1', 'D1', '', 0, false);
        (new Repositories($persistence, new RepositoryFactory()))->add($repository);

        $persistence->setStorageKey(Category::class);
        $category = new Category($repository, null, 'Category 1');
        (new Categories($persistence, new CategoryFactory()))->add($category);

        $persistence->setStorageKey(Package::class);
        $package = new Package();
        (new Packages($persistence, new PackageFactory()))->add($package);

        print_r($persistence);

        return $response;
    }
}
