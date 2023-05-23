<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/routes.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'])
    ->notEmpty();
