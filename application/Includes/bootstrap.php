<?php

require_once dirname(__DIR__, 2) . '/init.php';
require_once __DIR__ . '/helpers.php';

use RadioPanel\Core\Database;

global $conn;
$conn = Database::connection();

if (!empty($debugScripts)) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
