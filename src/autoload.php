<?php

spl_autoload_register(function ($class) {
    $map = [
        'RadioPanel\\' => __DIR__ . '/',
        'RadioPanel\\Controllers\\' => dirname(__DIR__) . '/application/Controllers/',
        'RadioPanel\\View\\' => dirname(__DIR__) . '/application/View/',
        'RadioPanel\\Http\\' => dirname(__DIR__) . '/application/Http/',
    ];

    foreach ($map as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});
