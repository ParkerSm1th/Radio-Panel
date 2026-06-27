<?php

namespace RadioPanel\Http;

class Router
{
    
    private $routes = [];

    
    public function match($methods, $pattern, $handler)
    {
        if (!is_array($methods)) {
            $methods = [$methods];
        }

        $this->routes[] = [
            'methods' => array_map('strtoupper', $methods),
            'pattern' => trim($pattern, '/'),
            'handler' => $handler,
        ];
    }

    public function get($pattern, $handler)
    {
        $this->match('GET', $pattern, $handler);
    }

    public function post($pattern, $handler)
    {
        $this->match('POST', $pattern, $handler);
    }

    
    public function dispatch(Request $request)
    {
        $path = $request->path();

        foreach ($this->routes as $route) {
            if (!in_array($request->method(), $route['methods'], true)) {
                continue;
            }

            $params = $this->matchPattern($route['pattern'], $path);
            if ($params !== null) {
                return [
                    'handler' => $route['handler'],
                    'params' => $params,
                ];
            }
        }

        return null;
    }

    
    private function matchPattern($pattern, $path)
    {
        if ($pattern === $path) {
            return [];
        }

        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (!is_int($key)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }
}
