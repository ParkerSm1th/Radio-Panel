<?php

namespace RadioPanel\Http;

class Request
{
    
    private $method;

    
    private $path;

    
    private $query;

    
    private $body;

    public function __construct($method, $path, array $query = [], array $body = [])
    {
        $this->method = strtoupper($method);
        $this->path = trim($path, '/');
        $this->query = $query;
        $this->body = $body;
    }

    public static function capture()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';
        $uri = \RadioPanel\Core\Paths::normalizeRequestPath($uri);
        $root = \RadioPanel\Core\Paths::webRootPath();
        $path = $uri;

        if ($root !== '' && strpos($path, $root) === 0) {
            $path = substr($path, strlen($root));
        }

        $path = trim($path, '/');

        return new self($method, $path, $_GET, $_POST);
    }

    public function method()
    {
        return $this->method;
    }

    public function path()
    {
        return $this->path;
    }

    
    public function query($key, $default = null)
    {
        return isset($this->query[$key]) ? $this->query[$key] : $default;
    }

    
    public function input($key, $default = null)
    {
        return isset($this->body[$key]) ? $this->body[$key] : $default;
    }
}
