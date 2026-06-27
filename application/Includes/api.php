<?php

require_once dirname(__DIR__, 2) . '/init.php';

use RadioPanel\Core\Auth;
use RadioPanel\Core\Security;

function apiRequireLogin()
{
    if (!Auth::check()) {
        http_response_code(401);
        echo 'unauthorized';
        exit;
    }
}

function apiRequireRole($minRole, array $options = [])
{
    apiRequireLogin();
    Auth::requireAccess($minRole, $options);
}

function apiValidateCsrf()
{
    $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!Security::validateCsrf($token)) {
        http_response_code(403);
        echo 'csrf_invalid';
        exit;
    }
}

function apiValidateKey()
{
    $key = isset($_REQUEST['api']) ? (string) $_REQUEST['api'] : '';
    if (!Security::validateApiKey($key)) {
        http_response_code(403);
        echo 'forbidden';
        exit;
    }
}
