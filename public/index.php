<?php

chdir(dirname(__DIR__));

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require "../vendor/autoload.php";
session_start();

$config['displayErrorDetails'] = true;

$app = new \Slim\App(['settings' => $config]);

require 'app/src/dependencies.php';
require 'app/src/middleware.php';

require 'app/src/routes.php';

//// verify repository
//// todo: add checks of login and key
//$app->get('/verify', function (Request $request, Response $response) {
//
//    /** @var Response $response */
//    $response = $response->withHeader('Content-Type', 'application/xml');
//    $response->getBody()->write($this->xml->write('status', ['verified' => 1]));
//
//    return $response;
//});

//// home page of repository
//$app->get('/home', function(Request $request, Response $response) {
//
//    $answer = [
//        'packages' => 1,
//        'downloads' => 5,
//        'topdownloaded' => [
//            'id' => '1',
//            'name' => 'Markdown Editor',
//            'downloads' => 1
//        ],
//        'newest' => [
//            'id' => '1',
//            'name' => 'Markdown Editor',
//            'package_name' => 'markdowneditor-1.0.0-pl',
//            'releasedon' => strftime("%Y-%m-%dT%H:%M:%SZ", time())
//        ],
//        'url' =>  'rest/package'
//    ];
//
//    $xml = $this->xml->write('home', $answer);
//
//    /** @var Response $response */
//    $response = $response->withHeader('Content-Type', 'application/xml');
//    $response = $response->getBody()->write($xml);
//
//    return $response;
//});

$app->run();
