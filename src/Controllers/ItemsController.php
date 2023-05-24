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

    public function edit(string $uuid)
    {
        parse_str(file_get_contents("php://input"),$data);
        $title = htmlspecialchars(trim($data['title']));
        if ($title == null) {
            $response = (new Response())->setHTTPStatusCode(HTTPStatusCode::UNPROCESSABLE_ENTITY)
                ->setStatus(ResponseStatus::UNPROCESSABLE_ENTITY)
                ->setMessage('title is a required field!');
            return $response->sendAsJson();
        }
        
        $response = new Response();
        $itemInstane = new Items();
        $item = $itemInstane->findByUuid($uuid);
        if ($item == []) {
            return $response->setHTTPStatusCode(HTTPStatusCode::NOT_FOUND)
                ->setStatus(ResponseStatus::NOT_FOUND)
                ->setMessage('The item was not found!')
                ->sendAsJson();
        }

        $deleteStatus = $itemInstane->update($uuid, $title);
        if (!$deleteStatus) {
            return $response->setHTTPStatusCode(HTTPStatusCode::SERVER_ERROR)
                ->setStatus(ResponseStatus::SERVER_ERROR)
                ->setMessage('The item is not updated, something is wrong!')
                ->sendAsJson();
        }

        $response->setMessage("The item was updated successfully")
            ->sendAsJson();
    }

    public function delete(string $uuid)
    {
        $response = new Response();
        $itemInstane = new Items();
        $item = $itemInstane->findByUuid($uuid);
        if ($item == []) {
            return $response->setHTTPStatusCode(HTTPStatusCode::NOT_FOUND)
                ->setStatus(ResponseStatus::NOT_FOUND)
                ->setMessage('The item was not found!')
                ->sendAsJson();
        }

        $deleteStatus = $itemInstane->deleteItemByUuid($uuid);
        if (!$deleteStatus) {
            return $response->setHTTPStatusCode(HTTPStatusCode::SERVER_ERROR)
                ->setStatus(ResponseStatus::SERVER_ERROR)
                ->setMessage('The item is not deleted, something is wrong!')
                ->sendAsJson();
        }

        $response->setMessage("The item was deleted successfully from the shopping list")
            ->sendAsJson();
    }
}
