<?php declare(strict_types = 1);

use Slim\App;

return static function (App $app) {

    $app->get('/home', HomeAction::class)->setName('home');

};
