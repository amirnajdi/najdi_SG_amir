<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{

    protected static ?PDO $instance = null;

    public static function connect()
    {
        if (self::$instance == null)
            self::createConnection();

        return self::$instance;
    }

    private static function createConnection()
    {
        $db_info = array(
            "db_host" => $_ENV['DB_HOST'],
            "db_port" => $_ENV['DB_PORT'],
            "db_user" => $_ENV['DB_USERNAME'],
            "db_pass" => $_ENV['DB_PASSWORD'],
            "db_name" => $_ENV['DB_DATABASE'],
            "db_charset" => "UTF-8",
            'unix_socket' => '/tmp/mysql.sock',
        );

        try {
            self::$instance = new PDO("mysql:host=" . $db_info['db_host'] . ';port=' . $db_info['db_port'] . ';dbname=' . $db_info['db_name'], $db_info['db_user'], $db_info['db_pass']);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec(
                "SET NAMES 'utf8';
                SET character_set_connection=utf8;
                SET character_set_client=utf8;
                SET character_set_results=utf8"
            );
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
}
