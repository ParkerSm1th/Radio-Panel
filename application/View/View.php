<?php

namespace RadioPanel\View;

use RadioPanel\Core\Asset;
use RadioPanel\Core\Paths;
use RadioPanel\Core\Security;

class View
{
    
    public static function render($template, array $data = [])
    {
        $file = Paths::join('application/Views/' . ltrim($template, '/'));

        if (!is_file($file)) {
            throw new \RuntimeException('View not found: ' . $template);
        }

        extract($data, EXTR_SKIP);
        include $file;
    }

    
    public static function e($value)
    {
        return Security::escape($value);
    }

    
    public static function asset($path)
    {
        return Asset::url($path);
    }

    
    public static function url($path)
    {
        return Paths::webPath($path);
    }

    
    public static function api($path)
    {
        return self::url('api/' . ltrim($path, '/'));
    }
}
