<?php declare(strict_types = 1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new DotEnv())
        ->usePutenv(true)
        ->loadEnv(__DIR__ . '/../.env');
} catch (Throwable $e) {
    exit($e->getMessage());
}

$builder = new ContainerBuilder();

(require __DIR__ . '/settings.php')($builder);
(require __DIR__ . '/dependencies.php')($builder);

AppFactory::setContainer($builder->build());
$application = AppFactory::create();

(require __DIR__ . '/middleware.php')($application);
(require __DIR__ . '/routes.php')($application);

return $application;
