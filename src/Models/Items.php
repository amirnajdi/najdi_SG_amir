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
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function findByUuid(string $uuid): array
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE uuid=:uuid");
            $statement->execute([
                'uuid' => $uuid
            ]);
            $result = $statement->fetch(PDO::FETCH_ORI_FIRST);
            return !$result ? [] : $result;
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function insertOneItem(string $title)
    {
        try {
            $statement = $this->connection->prepare("INSERT INTO {$this->table} (title) VALUES (:title)");
            $statement->execute([
                'title' => $title
            ]);
            return $this->getLastItemInserted();
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    private function getLastItemInserted(): array
    {
        try {
            $lastItemInsertedId = $this->connection->lastInsertId();
            return $this->findById($lastItemInsertedId);
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function findById(int $id): array
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id=:id");
            $statement->execute([
                'id' => $id
            ]);
            $result = $statement->fetch(PDO::FETCH_ORI_FIRST);
            return !$result ? [] : $result;
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function deleteItemByUuid(string $uuid): bool
    {
        try {
            $statement = $this->connection->prepare("DELETE FROM {$this->table} WHERE uuid = :uuid");
            $statement->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            return $statement->execute();
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function update(string $uuid, string $title): bool
    {
        try {
            $statement = $this->connection->prepare("UPDATE {$this->table} SET title = :title, updated_at = current_timestamp() WHERE uuid = :uuid");
            $statement->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $statement->bindParam(':title', $title, PDO::PARAM_STR);
            return $statement->execute();
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }
}
