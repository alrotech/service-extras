<?php

use Alroniks\Repository\Helpers\Renderer;
use Alroniks\Repository\Http\Controllers\HomeController;
use Alroniks\Repository\Http\Controllers\PackageController;
use Alroniks\Repository\Http\Controllers\RepositoryController;
use Alroniks\Repository\Http\Controllers\TestController;
use Alroniks\Repository\Middleware\ConfigReaderMiddleware;
use Interop\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = $app->getContainer();

// Output renderer
$container['renderer'] = function (ContainerInterface $container) {
    return new Renderer($container['request']);
};

// load persistence implementation
$container['persistence'] = function (ContainerInterface $container) {
    return new \Alroniks\Repository\Persistence\Redis();
};

// Repository initializer (configuration reader & loader)
$container['repository'] = function (ContainerInterface $container) {
    return new ConfigReaderMiddleware($container, 'config/repository.json');
};

// Controllers
$container[HomeController::class] = function (ContainerInterface $container) {
    return new HomeController($container);
};

$container[RepositoryController::class] = function (ContainerInterface $container) {
    return new RepositoryController($container);
};

$container[PackageController::class] = function (ContainerInterface $container) {
    return new PackageController($container);
};
