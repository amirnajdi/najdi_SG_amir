<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;

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


    public function all()
    {
        try {
            $statement = $this->connection->query("SELECT * FROM {$this->table}");
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException|Exception $exception) {
            return $exception->getMessage();
        }
    }
}
