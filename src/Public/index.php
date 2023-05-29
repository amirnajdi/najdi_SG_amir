<?php

require __DIR__ . '/../../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->safeLoad();
$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'JWT_KEY', 'APP_DOMAIN'])
    ->notEmpty();
$dotenv->ifPresent(['JWT_TOKEN_LIFE_TIME', 'DB_PORT'])->isInteger();

require __DIR__ . '/../routes.php';