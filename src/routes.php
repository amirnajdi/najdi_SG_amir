<?php

use Bramus\Router\Router;

$router = new Router();

// Config router
$router->setBasePath('/');

// Define routes
$router->get('health', fn() => http_response_code(200));

$router->get('items', "\App\Controllers\ItemsController@get");
$router->get('items/{uuid}', "\App\Controllers\ItemsController@show");

// Run router
$router->run();