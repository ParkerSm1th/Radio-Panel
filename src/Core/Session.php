<?php

namespace RadioPanel\Core;

class Session
{
    
    private static $started = false;

    public static function start()
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $lifetime = (int) Config::get('session.cookie_lifetime', 0);
        $path = '/';
        $domain = Config::get('session.cookie_domain', '');
        $secure = (bool) Config::get('session.cookie_secure', false);
        $httponly = (bool) Config::get('session.cookie_httponly', true);
        $samesite = Config::get('session.cookie_samesite', 'Lax');

        if (PHP_VERSION_ID >= 70300) {
            session_set_cookie_params([
                'lifetime' => $lifetime,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite,
            ]);
        } else {
            session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
        }

        session_start();
        self::$started = true;
    }

    
    public static function get($key, $default = null)
    {
        self::start();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function regenerate($deleteOld = true)
    {
        self::start();

        if (function_exists('session_regenerate_id')) {
            session_regenerate_id($deleteOld);
        }
    }

    public static function destroy()
    {
        self::start();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        self::$started = false;
    }
}
