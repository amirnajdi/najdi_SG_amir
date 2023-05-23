<?php

namespace App\Helpers;

enum ResponseStatus: string
{
    case SUCCESS = 'success';
    case SERVER_ERROR = 'Server Error';
    case NOT_FOUND = "Not Found";
}

enum HTTPStatusCode: int
{
    case SUCCESS = 200;
    case SERVER_ERROR = 500;
    case NOT_FOUND = 404;
}

class Response
{

    private array $data = [];
    private ResponseStatus $status = ResponseStatus::SUCCESS;

    public function setData(string $key, mixed $data)
    {
        $this->data[$key] = $data;
        return $this;
    }

    public function setStatus(ResponseStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    public function setHTTPStatusCode(HTTPStatusCode $statusCode)
    {
        http_response_code($statusCode->value);
        return $this;
    }

    public function sendAsJson()
    {
        echo json_encode([
            'status' => $this->status->value,
            'data' => $this->data
        ]);
    }
}
