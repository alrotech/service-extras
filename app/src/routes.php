<?php

$app->get('/verify', 'HomeController:verify')->setName('verify');
$app->get('/home', 'HomeController:index')->setName('home');

$app->get('/repository', 'RepositoryController:index')->setName('repository-list');
$app->get('/repository/{id:[0-9a-z]+}', 'RepositoryController:show')->setName('repository-single');

$app->get('/package', 'PackageController:search')->setName('package-search');
$app->get('/package/versions', 'PackageController:versions')->setName('package-versions');
$app->get('/package/update', 'PackageController:update')->setName('package-update');
