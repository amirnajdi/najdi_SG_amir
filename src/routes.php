<?php

use Bramus\Router\Router;

$router = new Router();

// Config router
$router->setBasePath('/');

// Define routes
$router->get('health', fn() => http_response_code(200));

// Run router
$router->run();