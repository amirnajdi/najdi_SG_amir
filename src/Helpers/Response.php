<?php

namespace App\Helpers;

enum ResponseStatus: string
{
    case SUCCESS = 'success';
    case CREATED = "Created";
    case SERVER_ERROR = 'Server Error';
    case NOT_FOUND = "Not Found";
    case UNPROCESSABLE_ENTITY = "Unprocessable Entity";
}

enum HTTPStatusCode: int
{
    case SUCCESS = 200;
    case CREATED = 201;
    case SERVER_ERROR = 500;
    case NOT_FOUND = 404;
    case UNPROCESSABLE_ENTITY = 422;
}

class Response
{

    private array $data = [];
    private ResponseStatus $status = ResponseStatus::SUCCESS;
    private ?string $message = null;

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

    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function sendAsJson()
    {
        header('Content-Type: application/json');
        $response = ['status' => $this->status->value];

        if ($this->message != null) $response['message'] = $this->message;
        if ($this->data != null) $response['data'] = $this->data;

        echo json_encode($response);
    }
}
