<?php

use RadioPanel\Core\Auth;
use RadioPanel\Core\Security;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'error';
    exit;
}

$csrf = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (!Security::validateCsrf($csrf)) {
    http_response_code(403);
    echo 'error';
    exit;
}

$username = Security::input('username', 'POST', '');
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

if ($username === '' || $password === '') {
    echo 'error';
    exit;
}

echo Auth::attempt($username, $password);
