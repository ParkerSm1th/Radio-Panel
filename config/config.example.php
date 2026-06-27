<?php

return [
    'app' => [
        'name' => 'Radio Panel',
        'url' => 'auto',
        'web_root' => 'auto',
        'app_path' => 'auto',
        'assets_path' => 'auto',
        'timezone' => 'Europe/London',
        'debug' => false,
    ],

    'database' => [
        'host' => 'localhost',
        'name' => 'panel_layout',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],

    'session' => [
        'cookie_domain' => '',
        'cookie_lifetime' => 0,
        'cookie_secure' => false,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ],

    'security' => [
        'csrf_enabled' => true,
        'api_key' => 'change_this_api_key',
    ],

    'azuracast' => [
        'url' => 'https://your-azuracast.example.com',
        'station' => 'your_station_shortcode',
        'api_key' => '',
    ],

    'gdpr' => [
        'enabled' => true,
        'privacy_url' => '/privacy.php',
        'cookie_consent_days' => 365,
        'log_retention_days' => 90,
    ],

    'logging' => [
        'enabled' => true,
        'path' => 'auto',
        'level' => 'error',
        'retention_days' => 90,
    ],
];
