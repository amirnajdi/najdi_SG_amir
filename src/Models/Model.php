<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

abstract class Model
{

    protected string $table;
    protected ?PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::connect();
    }
}
