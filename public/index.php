<?php declare(strict_types = 1);

try {
    (require __DIR__ . '/../config/application.php')->run();
} catch (Throwable $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}
