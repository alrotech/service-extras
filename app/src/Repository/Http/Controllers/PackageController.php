<?php declare(strict_types = 1);

namespace Alroniks\Repository\Http\Controllers;

use Alroniks\Repository\Domain\Package\Package;
use Alroniks\Repository\Domain\Package\PackageFactory;
use Alroniks\Repository\Domain\Package\Packages;
use Alroniks\Repository\Domain\Package\PackageTransformer;
use Alroniks\Repository\Domain\RecordNotFoundException;
use Alroniks\Repository\Helpers\GitHub;
use Alroniks\Repository\Middleware\ConfigReaderMiddleware;
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

    /** @var Packages */
    private $repository;

    /** @var Callable */
    private $renderer;

    /**
     * PackageController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->repository = new Packages($container->get('persistence'), new PackageFactory());
        $this->renderer = $this->container->get('renderer');
    }

    /**
     * @param ServerRequestInterface|Request $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function search(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        // search info about package by signature
        $signature = $request->getParam('signature', '');
        if ($signature) {
            $package = current($this->repository->findBy('signature', $signature));

            if (!$package) {
                throw new NotFoundException($request, $response);
            }

            /** @var Response $response */
            $response = call_user_func($this->renderer, $response, [
                'package' => PackageTransformer::transform($package)
            ]);

            return $response->withStatus(200);
        }

        // initialize parameters
        // TODO: вот тут нужно все таки сделать поиск еще и нечеткому имени, как-то через %% что ли
        // поиск только в name
        $query = $request->getParam('query', '');

        $page = intval($request->getParam('page', 0));
        $limit = intval($request->getParam('limit', 10));
        $tag = $request->getParam('tag', false);

        $pagination = $this->repository->paginate($page, $limit);

        $packages = $tag
            ? $this->repository->findBy('category', $tag)
            : $this->repository->findAll();

        foreach ($packages as &$package) {
            $package = PackageTransformer::transform($package);
        }

        /** @var Response $response */
        $response = call_user_func($this->renderer, $response, [
            'packages' => [
                '@attributes' => $pagination,
                'package' => array_values($packages)
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
        return $this->update($request, $response);
    }

    /**
     * @param ServerRequestInterface|Request $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function update(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $signature = $request->getParam('signature');
        list($name, $version) = explode('-', $signature);

        $version = intval(str_replace('.', '', $version));
        
        /** @var Package $package */
        $package = current($this->repository->findBy('name', $name));

        $error = ['error' => [
            'status' => 404,
            'property' => 'signature',
            'message' => 'Package not found.',
            'description' => "A package with the signature {$signature} was not found."
        ]];

        if (!$package) {
            return call_user_func($this->renderer, $response, $error);
        }

        if ($version >= intval(str_replace('.', '', $package->getVersion()))) {
            return call_user_func($this->renderer, $response, $error);
        }

        $response = call_user_func($this->renderer, $response, [
            'packages' => [
                '@attributes' => ['total' => 1],
                'package' => PackageTransformer::transform($package)
            ]
        ]);

        return $response;
    }

    /**
     * @param ServerRequestInterface|Request $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function reset(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $token = $request->getAttribute('token');

        $tokenStorage = 'config/token.key';

        if (!is_readable($tokenStorage) || trim(file_get_contents('config/token.key')) === '' || trim(file_get_contents('config/token.key')) !== $token) {
            return $response->withStatus(403, 'Access denied.');
        }

        $payload = json_decode($request->getBody()->getContents(), true);

        $githublink = $payload['repository']['html_url'] ?? '';

        if ($githublink === '') {
            return $response->withStatus(400, 'Link to repository not found in payload.');
        }

        if (!$package = $this->repository->findBy('githublink', $githublink)) {
            return $response->withStatus(404, 'Package not found.');
        }

        $this->repository->remove(current($package));

        // warming up cache
        $this->container->get('repository')->parseConfig($request);

        return $response->withStatus(200, 'Successfully reset.');
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
        
        $encodedLink = base64_encode(current($result));

        $body = $response->getBody();
        $body->write($this->container->get('router')
            ->setBasePath(join('://', [$request->getUri()->getScheme(), $request->getUri()->getAuthority()]))
            ->pathFor('package-direct-link', ['link' => $encodedLink]));

        return $response->withBody($body)->withStatus(200);
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
