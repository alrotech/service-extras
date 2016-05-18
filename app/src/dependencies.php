<?php

use alroniks\repository\controllers\Home;
use alroniks\repository\controllers\Package;
use alroniks\repository\controllers\Repository;
use alroniks\repository\Renderer;

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
    return new Repository($c['renderer']);
};

$container['PackageController'] = function ($c) {
    return new Package($c['renderer']);
};
