<?php

if (!function_exists('web_path')) {
    function web_path($path = '')
    {
        return \RadioPanel\Core\Paths::webPath($path);
    }
}

if (!defined('WEB_ROOT')) {
    define('WEB_ROOT', web_path());
}
