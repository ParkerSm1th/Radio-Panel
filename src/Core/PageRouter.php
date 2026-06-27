<?php

namespace RadioPanel\Core;

class PageRouter
{
    
    private static $allowedPages = null;

    
    public static function routeToPath($route)
    {
        $route = trim($route, '/');
        $parts = explode('.', $route, 2);

        if (count($parts) !== 2) {
            return null;
        }

        $area = $parts[0];
        $pageName = explode('?', $parts[1])[0];

        if (!preg_match('#^[A-Za-z]+$#', $area) || !preg_match('#^[A-Za-z0-9_]+$#', $pageName)) {
            return null;
        }

        return 'pages/' . $area . '/' . $pageName . '.php';
    }

    
    public static function resolvePage($page)
    {
        $page = str_replace('\\', '/', ltrim($page, '/'));

        if (!preg_match('#^pages/[A-Za-z]+/[A-Za-z0-9_]+\.php$#', $page)) {
            return null;
        }

        $viewsRoot = Paths::join('application/Views') . '/';
        $fullPath = realpath($viewsRoot . $page);

        if ($fullPath === false) {
            return null;
        }

        $viewsReal = realpath($viewsRoot);
        if ($viewsReal === false || strpos($fullPath, $viewsReal) !== 0) {
            return null;
        }

        if (!in_array($page, self::allowedPages(), true)) {
            return null;
        }

        return $fullPath;
    }

    
    public static function allowedPages()
    {
        if (self::$allowedPages !== null) {
            return self::$allowedPages;
        }

        self::$allowedPages = [];
        $pagesDir = Paths::join('application/Views/pages');

        if (!is_dir($pagesDir)) {
            return self::$allowedPages;
        }

        $areas = scandir($pagesDir);
        if ($areas === false) {
            return self::$allowedPages;
        }

        foreach ($areas as $area) {
            if ($area === '.' || $area === '..') {
                continue;
            }

            $areaPath = $pagesDir . '/' . $area;
            if (!is_dir($areaPath)) {
                continue;
            }

            $files = scandir($areaPath);
            if ($files === false) {
                continue;
            }

            foreach ($files as $file) {
                if (substr($file, -4) === '.php' && filesize($areaPath . '/' . $file) > 0) {
                    self::$allowedPages[] = 'pages/' . $area . '/' . $file;
                }
            }
        }

        return self::$allowedPages;
    }

    public static function appUrl($routeCode = 'Staff.Dashboard')
    {
        return Paths::absoluteApp($routeCode);
    }
}
