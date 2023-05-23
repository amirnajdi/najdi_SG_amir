<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Items;

class ItemsController
{

    public function get()
    {
        $items = (new Items())->all();

        $response = (new Response())->setData('items', $items);
        $response->sendAsJson();
    }
}
