<?php

namespace RadioPanel\Core;

class Asset
{
    
    public static function url($path)
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        $prefix = trim(Config::get('app.assets_path', Paths::assetsPath()), '/');

        return '/' . $prefix . '/' . $path;
    }

    public static function relative($path)
    {
        $assets = trim(Config::get('app.assets_path', Paths::assetsPath()), '/');
        $webRoot = ltrim(Paths::webRootPath(), '/');

        if ($webRoot !== '' && strpos($assets, $webRoot) === 0) {
            $suffix = substr($assets, strlen($webRoot) + 1);

            return $suffix . '/' . ltrim($path, '/');
        }

        return 'assets/' . ltrim($path, '/');
    }
}
