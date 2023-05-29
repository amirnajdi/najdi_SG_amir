<?php

namespace App\Controllers;

use App\Helpers\Authentication;
use App\Helpers\HTTPStatusCode;
use App\Helpers\Request;
use App\Helpers\Response;
use App\Helpers\ResponseStatus;

class UserController
{

    public function login()
    {
        $data = Request::getAllData();
        if (!isset($data['email']) || !isset($data['password'])) return $this->emailAndPasswordIsRequired();

        $token = Authentication::login($data['email'], $data['password']);
        if (!$token) {
            return (new Response())
                ->setStatus(ResponseStatus::UNAUTHORIZED)
                ->setHTTPStatusCode(HTTPStatusCode::UNAUTHORIZED)
                ->setMessage("Email or Password is wrong, Please try again")
                ->sendAsJson();
        }

        return (new Response())->setData('user', $token['user'])
            ->setData('token', $token['token'])
            ->setStatus(ResponseStatus::SUCCESS)
            ->setHTTPStatusCode(HTTPStatusCode::SUCCESS)
            ->sendAsJson();
    }

    private function emailAndPasswordIsRequired()
    {
        return (new Response())->setHTTPStatusCode(HTTPStatusCode::UNPROCESSABLE_ENTITY)
            ->setStatus(ResponseStatus::UNPROCESSABLE_ENTITY)
            ->setMessage('The Email and Password is required')
            ->sendAsJson();
    }
}
