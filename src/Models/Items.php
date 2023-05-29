<?php

namespace App\Models;

use App\Helpers\Authentication;
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

    private array $user;

    public function __construct()
    {
        parent::__construct();
        $this->table = "items";
        $this->user = Authentication::getCurrentUser();
    }


    public function all()
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE user_id=:user_id");
            $statement->bindParam(':user_id', $this->user['id'], PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function findByUuid(string $uuid): array
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE uuid=:uuid and user_id=:user_id");
            $statement->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $statement->bindParam(':user_id', $this->user['id'], PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ORI_FIRST);
            return !$result ? [] : $result;
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function insertOneItem(string $title)
    {
        try {
            $statement = $this->connection->prepare("INSERT INTO {$this->table} (title, user_id) VALUES (:title, :user_id)");
            $statement->bindParam(':title', $title, PDO::PARAM_STR);
            $statement->bindParam(':user_id', $this->user['id'], PDO::PARAM_INT);
            $statement->execute();
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
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id=:id and user_id=:user_id");
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':user_id', $this->user['id'], PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ORI_FIRST);
            return !$result ? [] : $result;
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function deleteItemByUuid(string $uuid): bool
    {
        try {
            $statement = $this->connection->prepare("DELETE FROM {$this->table} WHERE uuid = :uuid and user_id=:user_id");
            $statement->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $statement->bindParam(':user_id', $this->user['id'], PDO::PARAM_INT);
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

    public function updateStatus(string $uuid, bool $isDone)
    {
        try {
            $isDoneAt = $isDone ? date('d-m-y h:i:s') : null;
            $statement = $this->connection->prepare("UPDATE {$this->table} SET is_done_at = :is_done_at WHERE uuid = :uuid and user_id=:user_id");
            $statement->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $statement->bindParam(':is_done_at', $isDoneAt);
            $statement->bindParam(':user_id', $this->user['id'], PDO::PARAM_INT);
            return $statement->execute();
        } catch (PDOException | Exception $exception) {
            return $exception->getMessage();
        }
    }
}
