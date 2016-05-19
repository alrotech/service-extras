<?php

use Alroniks\Repository\Controllers\Home;
use Alroniks\Repository\Controllers\PackageController;
use Alroniks\Repository\Controllers\RepositoryController;
use Alroniks\Repository\Renderer;

$container = $app->getContainer();

// Output renderer
$container['renderer'] = function ($c) {
    return new Renderer($c['request']);
};

// Controllers
$container['HomeController'] = function ($c) {
    return new Home($c['renderer']);
};

$container['RepositoryController'] = function ($c) {
    return new RepositoryController($c['renderer']);
};

$container['PackageController'] = function ($c) {
    return new PackageController($c['renderer']);
};
