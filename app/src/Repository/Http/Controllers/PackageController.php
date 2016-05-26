<?php declare(strict_types = 1);

namespace Alroniks\Repository\Http\Controllers;

use Alroniks\Repository\Contracts\StorageInterface;
use Alroniks\Repository\Domain\Package\Package;
use Alroniks\Repository\Domain\Package\PackageFactory;
use Alroniks\Repository\Domain\Package\Packages;
use Alroniks\Repository\Domain\Package\PackageTransformer;
use Alroniks\Repository\Domain\RecordNotFoundException;
use Alroniks\Repository\Helpers\GitHub;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;

/**
 * Class PackageController
 * @package Alroniks\Repository\Http\Controllers
 */
class PackageController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * PackageController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        /** @var StorageInterface $persistence */
        $persistence = $container->get('persistence');
        $persistence->setStorageKey(Package::class);

        $this->repository = new Packages($persistence, new PackageFactory());
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function search(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        // search package by signature!!!
        //$signature = $request->getParam('signature', '');
        // getting info about package
        //$package = $this->packageStorage->findBy('signature', $signature);

        // сделать поиск по сигнатуре
        // отрефакторить стораджи для поиска по любому полю
        // добавить реализацию пагинации
        // добавить сортировку, если указана


        // потом
        // добавить middleware для проверки версии MODX и других параметров, чтобы показывать только совместимые пакеты
        // добавить middleware для проверки авторизации и доступа к определенным пакетам и репозиториям


        $query = $request->getParam('query', false); // ??

        $tag = $request->getParam('tag', false);

        // $sorter = $request->getParam('sorter', false);

        $start = $request->getParam('start', 0);
        $limit = $request->getParam('limit', 10);

        // filtering by tag
//        if ($tag) {
//            // todo: replace by universal findBy
//
//            $packages = $this->packageStorage->findByCategory($tag);
//        } else {
//            $packages = $this->packageStorage->all();
//        }

        $this->repository->add((new PackageFactory())->make([]));

        $packages = $this->repository->findAll();

        if (!count($packages)) {
            throw new NotFoundException($request, $response);
        }

        foreach ($packages as &$package) {
            $package = PackageTransformer::transform($package);
        }

        /** @var Response $response */
        $response = $this->container->get('renderer')->render($response, [
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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function versions(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function update(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function download(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $package = $request->getAttribute('id');

        try {
            /** @var Package $package */
            $package = $this->repository->find($package);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException($request, $response);
        }

        $ghLink = $package->getGitHubLink();

        list($owner, $repository) = explode('/',
            strtolower(str_replace('https://github.com/', '', $ghLink)));

        $result = GitHub::api($package->getStorage(), $owner, $repository, [
            CURLOPT_HTTPHEADER => ['Accept: application/octet-stream'],
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $encodedLink = base64_encode($result);
        
        return $this->container->get('router')
            ->setBasePath(join('://', [$request->getUri()->getScheme(), $request->getUri()->getAuthority()]))
            ->pathFor('package-direct-link', ['link' => $encodedLink]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function direct(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $link = $request->getAttribute('link');
        $decodedLink = base64_decode($link);

        return $response
            ->withHeader('Content-type', 'application/zip')
            ->withBody(new Stream(fopen($decodedLink, 'rb')));
    }
}
