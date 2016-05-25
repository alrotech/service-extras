<?php

use Alroniks\Repository\Http\Controllers\TestController;
use Alroniks\Repository\Initializer;
use Alroniks\Repository\Http\Controllers\HomeController;
use Alroniks\Repository\Controllers\PackageController;
use Alroniks\Repository\Http\Controllers\RepositoryController;
use Alroniks\Repository\Helpers\Renderer;
use Interop\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = $app->getContainer();

// Output renderer
$container['renderer'] = function (ContainerInterface $container) {
    return new Renderer($container['request']);
};

// load persistence implementation
$container['persistence'] = function (ContainerInterface $container) {
    return new \Alroniks\Repository\Persistence\Memory();
};

// Repository initializer (configuration loader)
$container['initializer'] = function ($c) {
    return new Initializer($c['router'], $c['persistence'], 'config/repository.json');
};

// Controllers
//$container[HomeController::class] = function ($c) {
//    return new HomeController($c['renderer'], $c['persistence']);
//};

$container[RepositoryController::class] = function ($container) {
    return new RepositoryController($container);
};

//$container[PackageController::class] = function ($c) {
//    return new PackageController($c['router'], $c['renderer'], $c['persistence']);
//};
