<?php

use Alroniks\Repository\Controllers\HomeController;
use Alroniks\Repository\Controllers\PackageController;
use Alroniks\Repository\Controllers\RepositoryController;

$app->get('/verify', [$container[HomeController::class], 'verify'])->setName('verify');
$app->get('/home', 'HomeController:index')->setName('home');

$app->get('/repository', [$container[RepositoryController::class], 'index'])->setName('repository-list');
$app->get('/repository/{id:[0-9a-z]+}', [$container[RepositoryController::class], 'show'])->setName('repository-single');

$app->get('/package', [$container[PackageController::class], 'search'])->setName('package-search');
$app->get('/package/versions', [$container[PackageController::class], 'versions'])->setName('package-versions');
$app->get('/package/update', [$container[PackageController::class], 'update'])->setName('package-update');
$app->get('/package/download/{id:[0-9a-z]+}', [$container[PackageController::class], 'download'])->setName('package-download');
