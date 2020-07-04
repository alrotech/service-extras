<?php declare(strict_types = 1);

use App\Action\VerifyAction;
use Slim\App;

return static function (App $app) {

    $app->get('/verify', VerifyAction::class)
        ->setName('verify');

};
