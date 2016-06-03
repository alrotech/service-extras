<?php

require 'recipe/composer.php';

// Set configurations
set('repository', 'git@github.com:Alroniks/aes-repository.git');
set('shared_files', []);
set('shared_dirs', [
    'config'
]);
set('writable_dirs', []);


server('production', 'api.aestore.by')
    ->user('alroniks')
    ->identityFile()
    ->env('deploy_path', '/var/www/apiaestore')
    ->stage('production');

task('php:restart', function () {
    run('sudo /usr/sbin/service php7.0-fpm restart');
})->desc('Restart PHP-FPM service');

task('cache:flush', function () {
    run('redis-cli -n 1 flushall');
});

after('success', 'php:restart');

after('deploy:update_code', 'deploy:shared');
