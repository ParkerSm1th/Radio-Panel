<?php

namespace RadioPanel\Core;

class Security
{
    
    public static function escape($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    
    public static function escapeJs($value)
    {
        return json_encode((string) $value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    
    public static function sanitizeString($input)
    {
        if ($input === null) {
            return '';
        }

        $input = trim($input);
        $input = strip_tags($input);

        return $input;
    }

    
    public static function input($key, $method = 'POST', $default = null)
    {
        $source = strtoupper($method) === 'GET' ? $_GET : $_POST;

        if (!isset($source[$key])) {
            return $default;
        }

        return self::sanitizeString($source[$key]);
    }

    public static function clientIp()
    {
        $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];

        foreach ($keys as $key) {
            if (empty($_SERVER[$key])) {
                continue;
            }

            $ip = $_SERVER[$key];

            if ($key === 'HTTP_X_FORWARDED_FOR' && strpos($ip, ',') !== false) {
                $parts = explode(',', $ip);
                $ip = trim($parts[0]);
            }

            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        return '0.0.0.0';
    }

    
    public static function sanitizeRedirect($url)
    {
        if ($url === null || $url === '') {
            return '';
        }

        if (strpos($url, '//') === 0) {
            return '';
        }

        if (preg_match('#^https?://#i', $url)) {
            $origin = rtrim(Paths::origin(), '/');
            if ($origin !== '' && strpos($url, $origin) === 0) {
                return $url;
            }

            return '';
        }

        if ($url[0] !== '/') {
            $url = '/' . $url;
        }

        return $url;
    }

    public static function csrfToken()
    {
        Session::start();

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }

    
    public static function validateCsrf($token)
    {
        if (!Config::get('security.csrf_enabled', true)) {
            return true;
        }

        Session::start();
        $stored = isset($_SESSION['_csrf_token']) ? $_SESSION['_csrf_token'] : '';

        return is_string($token) && $stored !== '' && hash_equals($stored, $token);
    }

    
    public static function validateApiKey($provided)
    {
        $expected = Config::get('security.api_key', '');
        return $expected !== '' && is_string($provided) && hash_equals($expected, $provided);
    }
}
