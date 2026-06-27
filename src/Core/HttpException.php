<?php

namespace RadioPanel\Core;

class HttpException extends \RuntimeException
{
    private $statusCode;

    public function __construct($message, $statusCode = 500, \Throwable $previous = null)
    {
        parent::__construct((string) $message, 0, $previous);
        $this->statusCode = (int) $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
