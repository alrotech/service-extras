<?php

namespace Alroniks\Repository\Controllers;

use Alroniks\Repository\Contracts\PersistenceInterface;
use alroniks\repository\Renderer;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomeController
 * @package Alroniks\Repository\Controllers
 */
class HomeController
{
    /** @var Renderer */
    private $renderer;

    /**
     * Home constructor.
     * @param Renderer $renderer
     * @param PersistenceInterface $persistence
     */
    public function __construct(Renderer $renderer, PersistenceInterface $persistence)
    {
        $this->renderer = $renderer;
    }

    /**
     * Verifies user credentials and access to the repository
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function verify(Request $request, Response $response)
    {
        // todo: add full check of user and key and access from site to repository
        /** @var Response $response */
        $response = $this->renderer->render($response, [
            'status' => ['verified' => 1]
        ]);
        $response->withStatus(200);

        return $response;
    }

    /**
     * Shows actual stats about connected repository
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response)
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
        $response = $this->renderer->render($response, $answer);
        $response->withStatus(200);

        return $response;
    }
}
