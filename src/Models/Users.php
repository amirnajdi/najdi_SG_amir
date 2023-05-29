<?php

namespace App\Models;

use Exception;
use PDO;
use PDOException;

class Users extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "users";
    }


    public function findByEmail(string $email): array
     {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE email=:email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ORI_FIRST);
            return !$result ? [] : $result;
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function findByUuid(string $uuid): array
    {
       try {
           $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE uuid=:uuid");
           $statement->bindParam(':uuid', $uuid, PDO::PARAM_STR);
           $statement->execute();
           $result = $statement->fetch(PDO::FETCH_ORI_FIRST);
           return !$result ? [] : $result;
       } catch (PDOException | Exception $exception) {
           return $exception->getMessage();
       }
   }
}
