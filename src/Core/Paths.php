<?php

namespace RadioPanel\Core;

class Paths
{
    public static function root()
    {
        if (defined('RADIO_PANEL_ROOT')) {
            return RADIO_PANEL_ROOT;
        }

        return dirname(__DIR__);
    }

    public static function origin()
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        $scheme = $https ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

        return $scheme . '://' . $host;
    }

    public static function webRootPath()
    {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }

        $fromDocRoot = self::webRootFromDocumentRoot();
        if ($fromDocRoot !== null) {
            return $cached = $fromDocRoot;
        }

        $fromScript = self::webRootFromScript(self::scriptWebPath());
        if ($fromScript !== null) {
            return $cached = $fromScript;
        }

        $fromRequest = self::webRootFromRequestUri();
        if ($fromRequest !== null) {
            return $cached = $fromRequest;
        }

        return $cached = '';
    }

    public static function webPath($path = '')
    {
        $root = self::webRootPath();
        $path = ltrim(str_replace('\\', '/', (string) $path), '/');

        if ($path === '') {
            return ($root !== '' ? $root : '') . '/';
        }

        return ($root !== '' ? $root : '') . '/' . $path;
    }

    public static function absolute($path = '')
    {
        return rtrim(self::origin(), '/') . self::webPath($path);
    }

    public static function appPath()
    {
        return self::webPath('app');
    }

    public static function apiPath()
    {
        return self::webPath('api');
    }

    public static function assetsPath()
    {
        return self::webPath('assets');
    }

    public static function absoluteFromWebPath($path)
    {
        $full = (string) $path;
        $pathOnly = parse_url($full, PHP_URL_PATH);
        if ($pathOnly === null || $pathOnly === false || $pathOnly === '') {
            $pathOnly = $full;
        }

        $pathOnly = self::normalizeRequestPath($pathOnly);
        $query = parse_url($full, PHP_URL_QUERY);
        $url = rtrim(self::origin(), '/') . $pathOnly;

        if ($query) {
            $url .= '?' . $query;
        }

        return $url;
    }

    public static function appUrl()
    {
        return rtrim(self::absolute(''), '/');
    }

    public static function absoluteApp($route = 'Staff.Dashboard', $query = '')
    {
        $route = ltrim(str_replace('\\', '/', (string) $route), '/');
        $url = rtrim(self::origin(), '/') . rtrim(self::appPath(), '/') . '/' . $route;

        if ($query !== '') {
            $url .= '?' . ltrim((string) $query, '?&');
        }

        return $url;
    }

    public static function normalizeRequestPath($path)
    {
        $path = str_replace('\\', '/', (string) $path);

        if ($path === '' || $path === false) {
            return '/';
        }

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        if (self::looksLikeFilesystemPath($path)) {
            $appRoot = realpath(self::root());
            if ($appRoot) {
                $appRoot = str_replace('\\', '/', $appRoot);
                if (strpos($path, $appRoot) === 0) {
                    $suffix = substr($path, strlen($appRoot));
                    if ($suffix === '' || $suffix === false) {
                        $suffix = '/';
                    }
                    $webRoot = self::webRootFromDocumentRoot();
                    if ($webRoot === null) {
                        $webRoot = self::webRootFromScript(self::scriptWebPath()) ?? '';
                    }
                    return ($webRoot !== '' ? $webRoot : '') . $suffix;
                }
            }

            if (preg_match('#/(app|api|logout)(/.*)?$#', $path, $matches)) {
                $webRoot = self::webRootFromDocumentRoot();
                if ($webRoot === null) {
                    $webRoot = '';
                }
                return ($webRoot !== '' ? $webRoot : '') . '/' . $matches[1] . ($matches[2] ?? '');
            }
        }

        return $path;
    }

    public static function join($path)
    {
        return self::root() . '/' . ltrim(str_replace('\\', '/', $path), '/');
    }

    public static function storagePath()
    {
        return self::join('storage');
    }

    public static function storageLogsPath()
    {
        return self::join('storage/logs');
    }

    public static function profilePicturesPath()
    {
        return self::join('profilePictures');
    }

    public static function ensureRuntimeDirectories()
    {
        $directories = [
            self::storagePath(),
            self::storageLogsPath(),
            self::profilePicturesPath(),
        ];

        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                continue;
            }

            if (!@mkdir($directory, 0755, true) && !is_dir($directory)) {
                trigger_error('Unable to create directory: ' . $directory, E_USER_WARNING);
            }
        }
    }

    private static function webRootFromDocumentRoot()
    {
        $appRoot = realpath(self::root());
        $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;

        if (!$appRoot || !$docRoot || strpos($appRoot, $docRoot) !== 0) {
            return null;
        }

        $relative = substr($appRoot, strlen($docRoot));

        return self::normalizeWebRoot($relative);
    }

    private static function webRootFromScript($scriptPath)
    {
        if ($scriptPath === null || $scriptPath === '') {
            return null;
        }

        $dir = dirname($scriptPath);

        return self::normalizeWebRoot($dir);
    }

    private static function webRootFromRequestUri()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
        if (!$uri) {
            return null;
        }

        $uri = self::normalizeRequestPath($uri);

        if (preg_match('#^(.+?)/(?:app|api|logout)(?:/|$)#', $uri, $matches)) {
            return self::normalizeWebRoot($matches[1]);
        }

        if (preg_match('#^(.+?)/(?:index\.php|router\.php)$#', $uri, $matches)) {
            return self::normalizeWebRoot($matches[1]);
        }

        return null;
    }

    private static function scriptWebPath()
    {
        $candidates = [];

        if (!empty($_SERVER['SCRIPT_NAME'])) {
            $candidates[] = $_SERVER['SCRIPT_NAME'];
        }

        if (!empty($_SERVER['PHP_SELF'])) {
            $candidates[] = $_SERVER['PHP_SELF'];
        }

        foreach ($candidates as $candidate) {
            $candidate = str_replace('\\', '/', $candidate);

            if (self::looksLikeFilesystemPath($candidate)) {
                continue;
            }

            return $candidate;
        }

        foreach ($candidates as $candidate) {
            $candidate = self::normalizeRequestPath($candidate);
            if ($candidate !== '/' && $candidate !== '') {
                return $candidate;
            }
        }

        return null;
    }

    private static function normalizeWebRoot($path)
    {
        $path = str_replace('\\', '/', trim((string) $path));

        if ($path === '' || $path === '.' || $path === '/') {
            return '';
        }

        if (self::looksLikeFilesystemPath($path)) {
            return null;
        }

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $path = self::stripRouteSuffixes($path);

        if ($path === '/' || $path === '') {
            return '';
        }

        return rtrim($path, '/');
    }

    private static function stripRouteSuffixes($path)
    {
        $path = preg_replace('#(/app)(/.*)?$#', '', $path);
        $path = preg_replace('#(/api)(/.*)?$#', '', $path);
        $path = preg_replace('#/panel(/.*)?$#', '', $path);
        $path = preg_replace('#/(index|router)\.php$#', '', $path);

        return $path === '' ? '/' : $path;
    }

    private static function looksLikeFilesystemPath($path)
    {
        $path = str_replace('\\', '/', (string) $path);

        return (bool) preg_match('#/(?:Applications|Users|home|var|opt|private|Volumes|xamppfiles|htdocs)/#i', $path)
            || (bool) preg_match('#^[A-Za-z]:/#', $path);
    }
}
