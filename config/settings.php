<?php declare(strict_types = 1);

use DI\ContainerBuilder;

error_reporting(0);
ini_set('display_errors', '0');

date_default_timezone_set('Europe/Minsk');

return static function (ContainerBuilder $builder) {
    $builder->addDefinitions();
};
