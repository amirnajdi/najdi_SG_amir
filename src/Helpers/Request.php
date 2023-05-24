<?php

namespace App\Helpers;

class Request
{

    public static function getAllData(): array
    {
        $data =  match ($_SERVER['REQUEST_METHOD']) {
            'GET' => self::handelGetRequstData(),
            'POST' => self::handelPostRequstData(),
            'PUT' => self::handelPutRequstData()
        };

        return self::cleaningData($data);
    }

    private static function handelGetRequstData(): array
    {
        return $_GET;
    }

    private static function handelPostRequstData(): array
    {
        return [...$_POST, ...self::handelGetRequstData()];
    }

    private static function handelPutRequstData(): array
    {
        $putData = [];
        parse_str(file_get_contents("php://input"), $putData);
        return [...$putData, ...self::handelGetRequstData()];
    }

    private static function cleaningData(array $data): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(trim($value));
        }

        return $data;
    }
}
