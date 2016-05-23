<?php

use Alroniks\Repository\Initializer;
use Alroniks\Repository\Controllers\HomeController;
use Alroniks\Repository\Controllers\PackageController;
use Alroniks\Repository\Controllers\RepositoryController;
use Alroniks\Repository\InMemoryPersistence;
use Alroniks\Repository\Renderer;

$container = $app->getContainer();

// Output renderer
$container['renderer'] = function ($c) {
    return new Renderer($c['request']);
};

// Persistence
$container['persistence'] = function ($c) {
    return new \Alroniks\Repository\Persistence\Redis();
};

// Repository initializer (configuration loader)
$container['initializer'] = function ($c) {
    return new Initializer($c['router'], $c['persistence'], 'config/repository.json');
};

// Controllers
$container[HomeController::class] = function ($c) {
    return new HomeController($c['renderer'], $c['persistence']);
};

$container[RepositoryController::class] = function ($c) {
    return new RepositoryController($c['renderer'], $c['persistence']);
};

$container[PackageController::class] = function ($c) {
    return new PackageController($c['renderer'], $c['persistence']);
};
