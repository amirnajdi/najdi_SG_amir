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
        if ($item == []) return $this->itemNotFound();

        $response = (new Response())->setData('item', $item);
        $response->sendAsJson();
    }

    public function create()
    {
        $title = htmlspecialchars(trim($_POST['title']));
        if ($title == null) return $this->titleIsRequired();

        $item = (new Items)->insertOneItem($title);

        $response = (new Response())->setData('item', $item)
            ->setStatus(ResponseStatus::CREATED)
            ->setHTTPStatusCode(HTTPStatusCode::CREATED)
            ->setMessage("The new item add successfully to your shopping list")
            ->sendAsJson();
    }

    public function edit(string $uuid)
    {
        parse_str(file_get_contents("php://input"), $data);
        $title = htmlspecialchars(trim($data['title']));
        if ($title == null) return $this->titleIsRequired();

        $response = new Response();
        $itemInstane = new Items();

        $item = $itemInstane->findByUuid($uuid);
        if ($item == []) return $this->itemNotFound();

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
        if ($item == []) return $this->itemNotFound();

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

    public function toggleStatus(string $uuid)
    {
        $response = new Response();
        $itemInstane = new Items();

        $item = $itemInstane->findByUuid($uuid);
        if ($item == []) return $this->itemNotFound();

        // if item is_done_at is null, it means the item is not done yet and we change the is_done_at to the current date,
        // and if is_done_at is not null it means is done and we change to null
        $changeStatusTo = $item['is_done_at'] == null ? true : false;

        $deleteStatus = $itemInstane->updateStatus($uuid, $changeStatusTo);
        if (!$deleteStatus) {
            return $response->setHTTPStatusCode(HTTPStatusCode::SERVER_ERROR)
                ->setStatus(ResponseStatus::SERVER_ERROR)
                ->setMessage('The item is not updated, something is wrong!')
                ->sendAsJson();
        }

        $response->setMessage("The shopping list item status was updated successfully")
            ->sendAsJson();
    }

    private function titleIsRequired()
    {
        return (new Response())->setHTTPStatusCode(HTTPStatusCode::UNPROCESSABLE_ENTITY)
            ->setStatus(ResponseStatus::UNPROCESSABLE_ENTITY)
            ->setMessage('title is a required field!')
            ->sendAsJson();
    }

    private function itemNotFound()
    {
        return (new Response())->setHTTPStatusCode(HTTPStatusCode::NOT_FOUND)
            ->setStatus(ResponseStatus::NOT_FOUND)
            ->setMessage('The item was not found!')
            ->sendAsJson();
    }
}
