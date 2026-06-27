<?php

namespace RadioPanel\Core;

class Config
{
    
    private static $config = null;

    
    public static function all()
    {
        if (self::$config === null) {
            self::load();
        }

        return self::$config;
    }

    
    public static function get($key, $default = null)
    {
        $config = self::all();
        $parts = explode('.', $key);
        $value = $config;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }
            $value = $value[$part];
        }

        return $value;
    }

    private static function load()
    {
        $root = Paths::root();
        $configFile = $root . '/config/config.php';
        $exampleFile = $root . '/config/config.example.php';

        if (file_exists($configFile)) {
            self::$config = require $configFile;
        } elseif (file_exists($exampleFile)) {
            self::$config = require $exampleFile;
        } else {
            self::$config = [];
        }

        self::applyEnvironmentOverrides();
        self::applyRuntimePaths();
    }

    private static function applyEnvironmentOverrides()
    {
        $envMap = [
            'APP_URL' => ['app', 'url'],
            'APP_DEBUG' => ['app', 'debug'],
            'DB_HOST' => ['database', 'host'],
            'DB_NAME' => ['database', 'name'],
            'DB_USER' => ['database', 'user'],
            'DB_PASS' => ['database', 'pass'],
            'API_KEY' => ['security', 'api_key'],
            'AZURACAST_URL' => ['azuracast', 'url'],
            'AZURACAST_STATION' => ['azuracast', 'station'],
            'AZURACAST_API_KEY' => ['azuracast', 'api_key'],
            'LOG_ENABLED' => ['logging', 'enabled'],
            'LOG_LEVEL' => ['logging', 'level'],
        ];

        foreach ($envMap as $envKey => $path) {
            $value = getenv($envKey);
            if ($value !== false && $value !== '') {
                if ($path[0] === 'app' && $path[1] === 'debug') {
                    self::setNested($path, filter_var($value, FILTER_VALIDATE_BOOLEAN));
                    continue;
                }

                if ($path[0] === 'logging' && $path[1] === 'enabled') {
                    self::setNested($path, filter_var($value, FILTER_VALIDATE_BOOLEAN));
                    continue;
                }

                self::setNested($path, $value);
            }
        }
    }

    private static function applyRuntimePaths()
    {
        $url = self::get('app.url');
        if ($url === null || $url === '' || $url === 'auto') {
            self::setNested(['app', 'url'], Paths::origin());
        }

        $webRoot = self::get('app.web_root');
        if ($webRoot === null || $webRoot === '' || $webRoot === 'auto') {
            self::setNested(['app', 'web_root'], Paths::webRootPath());
        }

        $appPath = self::get('app.app_path');
        if ($appPath === null || $appPath === '' || $appPath === 'auto') {
            self::setNested(['app', 'app_path'], Paths::appPath());
        }

        $assetsPath = self::get('app.assets_path');
        if ($assetsPath === null || $assetsPath === '' || $assetsPath === 'auto') {
            self::setNested(['app', 'assets_path'], Paths::assetsPath());
        }
    }

    
    private static function setNested(array $path, $value)
    {
        $ref = &self::$config;

        foreach ($path as $i => $segment) {
            if ($i === count($path) - 1) {
                $ref[$segment] = $value;
                return;
            }

            if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                $ref[$segment] = [];
            }

            $ref = &$ref[$segment];
        }
    }
}
