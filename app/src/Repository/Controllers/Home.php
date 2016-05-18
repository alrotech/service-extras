<?php

namespace alroniks\repository\controllers;

use alroniks\repository\Renderer;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Home
 * @package alroniks\repository\controllers
 */
class Home
{
    private $renderer;

    /**
     * Home constructor.
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
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
        $answer = [
            'packages' => '1',
            'downloads' => '5',
            'url' =>  '/rest/package',
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
