<?php declare(strict_types = 1);

namespace Alroniks\Repository\Http\Controllers;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Class HomeController
 * @package Alroniks\Repository\Controllers
 */
class HomeController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * HomeController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->renderer = $this->container->get('renderer');
    }

    /**
     * Verifies user credentials and access to the repository
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function verify(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        // todo: add full check of user and key and access from site to repository
        /** @var Response $response */
        $response = $this->container->get('renderer')->__invoke($response, [
            'status' => ['verified' => 1]
        ]);

        return $response->withStatus(200);
    }

    /**
     * Shows actual stats about connected repository
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return static
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        // todo: collect real stats from repository (get data about available packages for this user)
        $answer = [
            'packages' => '1',
            'downloads' => '5',
            'topdownloaded' => [
                'id' => '1',
                'name' => 'Markdown Editor',
                'downloads' => '1'
            ],
            'newest' => [
                'id' => '1',
                'name' => 'Markdown Editor',
                'package_name' => 'markdowneditor-1.0.0-pl',
                'releasedon' => strftime("%Y-%m-%dT%H:%M:%SZ", time())
            ]
        ];

        /** @var Response $response */
        $response = $this->container->get('renderer')->__invoke($response, $answer);

        return $response->withStatus(200);
    }
}
