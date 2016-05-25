<?php

use Alroniks\Repository\Http\Controllers\HomeController;
use Alroniks\Repository\Controllers\PackageController;
use Alroniks\Repository\Http\Controllers\RepositoryController;
use Alroniks\Repository\Http\Controllers\TestController;

$app->get('/', [$container[TestController::class], 'test']);

//// verifies credentials and site when adding new package provider through Package Manager
//$app->get('/verify', [$container[HomeController::class], 'verify'])
//    ->setName('verify');
//
//// shows statistics about most downloaded ans newest packages in repository
//$app->get('/home', 'HomeController:index')
//    ->setName('home');

# shows list of available repositories
$app->get('/repository', [$container[RepositoryController::class], 'index'])
    ->setName('repository-list');

# shows details of requested repository
$app->get('/repository/{id:[0-9a-z]+}', [$container[RepositoryController::class], 'show'])
    ->setName('repository-single');

//// shows list of packages, that filtered by search parameters
//$app->get('/package', [$container[PackageController::class], 'search'])
//    ->setName('package-search');
//
//// returns list of available versions of package (for check updates)
//$app->get('/package/versions', [$container[PackageController::class], 'versions'])
//    ->setName('package-versions');
//
//// requests update of installed package
//$app->get('/package/update', [$container[PackageController::class], 'update'])
//    ->setName('package-update');
//
//// gets link to package form github for download and install
//$app->get('/package/download/{id:[0-9a-z]+}', [$container[PackageController::class], 'download'])
//    ->setName('package-download');
//
//// proxy for direct link to package (on amazon), because MODX adds additional parameter
//// to request, that brake signature of query string to file and causes an error
//$app->get('/package/direct/{link}', [$container[PackageController::class], 'direct'])
//    ->setName('package-direct-link');
