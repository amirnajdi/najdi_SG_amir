<?php

namespace App\Controllers;

use App\Helpers\HTTPStatusCode;
use App\Helpers\Response;
use App\Helpers\ResponseStatus;
use App\Models\Items;

class ItemsController
{

    public function get()
    {
        $items = (new Items())->all();

        $response = (new Response())->setData('items', $items);
        $response->sendAsJson();
    }

    public function show(string $uuid)
    {
        $item = (new Items())->findByUuid($uuid);
        if ($item == []) {
            $response = (new Response())->setHTTPStatusCode(HTTPStatusCode::NOT_FOUND)
                ->setStatus(ResponseStatus::NOT_FOUND);
            return $response->sendAsJson();
        }

        $response = (new Response())->setData('item', $item);
        $response->sendAsJson();
    }

    public function create()
    {
        $title = htmlspecialchars(trim($_POST['title']));
        if ($title == null) {
            $response = (new Response())->setHTTPStatusCode(HTTPStatusCode::UNPROCESSABLE_ENTITY)
                ->setStatus(ResponseStatus::UNPROCESSABLE_ENTITY)
                ->setMessage('title is a required field!');
            return $response->sendAsJson();
        }

        $item = (new Items)->insertOneItem($title);

        $response = (new Response())->setData('item', $item)
            ->setStatus(ResponseStatus::CREATED)
            ->setHTTPStatusCode(HTTPStatusCode::CREATED);

        $response->sendAsJson();
    }
}
