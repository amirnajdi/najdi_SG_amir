<?php

use App\Helpers\Response;
use Bramus\Router\Router;
use App\Helpers\Authentication;
use App\Helpers\HTTPStatusCode;
use App\Helpers\ResponseStatus;


$router = new Router();

// Config router
$router->setBasePath('/');

// Define routes
$router->get('health', fn () => http_response_code(200));

$router->post('login', "\App\Controllers\UserController@login");


$router->before('GET|POST|DELETE|PUT', '/items/*', function () {
    if (!Authentication::isUserAuthenticated()) {
        (new Response())->setHTTPStatusCode(HTTPStatusCode::UNAUTHORIZED)
            ->setStatus(ResponseStatus::UNAUTHORIZED)
            ->setMessage('Unauthorized')
            ->sendAsJson();
        exit();
    }
});
$router->get('items', "\App\Controllers\ItemsController@get");
$router->post('items', "\App\Controllers\ItemsController@create");
$router->get('items/{uuid}', "\App\Controllers\ItemsController@show");
$router->put('items/{uuid}/status/toggle', "\App\Controllers\ItemsController@toggleStatus");
$router->put('items/{uuid}', "\App\Controllers\ItemsController@edit");
$router->delete('items/{uuid}', "\App\Controllers\ItemsController@delete");

// Run router
$router->run();