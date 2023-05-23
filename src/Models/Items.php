<?php

namespace App\Models;

class Items extends Model
{

    public int $id;
    public string $title;
    public bool $is_checked;
    public string $uuid;
    public string $created_at;
    public string $updated_at;

    public function __construct()
    {
        parent::__construct();
        $this->table = "items";
    }
}
