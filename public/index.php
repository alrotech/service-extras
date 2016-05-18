<?php

chdir(dirname(__DIR__));

require "vendor/autoload.php";
session_start();

$config['displayErrorDetails'] = true;

$app = new \Slim\App(['settings' => $config]);

require 'app/src/dependencies.php';
require 'app/src/middleware.php';

require 'app/src/routes.php';

$app->run();
