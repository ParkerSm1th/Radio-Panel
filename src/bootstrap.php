<?php

if (!defined('RADIO_PANEL_ROOT')) {
    define('RADIO_PANEL_ROOT', dirname(__DIR__));
}

require_once RADIO_PANEL_ROOT . '/src/autoload.php';

use RadioPanel\Core\Config;
use RadioPanel\Core\Database;
use RadioPanel\Core\ErrorHandler;
use RadioPanel\Core\Paths;
use RadioPanel\Core\Session;

Paths::ensureRuntimeDirectories();

$timezone = Config::get('app.timezone', 'Europe/London');
date_default_timezone_set($timezone);

if (Config::get('app.debug', false)) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}

ErrorHandler::register();

Session::start();

global $conn;
$conn = Database::connection();
Database::ensureSchema();
