<?php

namespace RadioPanel\Http;

class Response
{
    
    private $status;

    
    private $body;

    
    private $headers;

    public function __construct($body = '', $status = 200, array $headers = [])
    {
        $this->body = (string) $body;
        $this->status = (int) $status;
        $this->headers = $headers;
    }

    public static function html($body, $status = 200)
    {
        return new self($body, $status, ['Content-Type: text/html; charset=utf-8']);
    }

    public static function text($body, $status = 200)
    {
        return new self($body, $status, ['Content-Type: text/plain; charset=utf-8']);
    }

    public static function json(array $data, $status = 200)
    {
        $body = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($body === false) {
            $body = '{"error":"Unable to encode response"}';
            $status = 500;
        }

        return new self($body, $status, ['Content-Type: application/json; charset=utf-8']);
    }

    public static function error($message, $status = 500, array $extra = [])
    {
        return self::json(array_merge([
            'error' => (string) $message,
            'status' => (int) $status,
        ], $extra), $status);
    }

    public static function redirect($url, $status = 302)
    {
        return new self('', $status, ['Location: ' . $url]);
    }

    public function send()
    {
        http_response_code($this->status);

        foreach ($this->headers as $header) {
            header($header);
        }

        echo $this->body;
    }
}
