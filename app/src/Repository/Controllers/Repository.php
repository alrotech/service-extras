<?php

namespace alroniks\repository\controllers;

use alroniks\repository\Renderer;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Repository
 * @package alroniks\repository\controllers
 */
class Repository
{
    private $renderer;

    /**
     * Repository constructor.
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function index(Request $request, Response $response)
    {
        $answer = [
            'repositories' => [
                '@attributes' => [
                    'type' => 'array',
                    'of' => 5,
                    'total' => 50,
                    'page' => 2,
                ],
                'repository' => [
                    [
                        'id' => 1,
                        'name' => 'Special repo',
                        'description' => [
                            '@cdata' => 'desc'
                        ],
                        'createdon' => '2011-02-04T18:05:07+0000',
                        'rank' => 0,
                        'packages' => 500,
                        'templated' => 0
                    ]
                ]

            ]
        ];

        /** @var Response $response */
        $response = $this->renderer->render($response, $answer);
        $response->withStatus(200);

        return $response;
    }
    
    public function show(Request $request, Response $response, $params)
    {
        $body = $request->getBody();

        file_put_contents('body.txt', print_r($request->getQueryParams(), true));

        $answer = [];

        /** @var Response $response */
        $response = $this->renderer->render($response, $answer);
        $response->withStatus(200);
    }
}
