<?php

use RadioPanel\Core\Asset;
use RadioPanel\Core\Auth;
use RadioPanel\Core\Database;
use RadioPanel\Core\Paths;
use RadioPanel\Core\Security;

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['loginSearch']) || $_GET['loginSearch'] != '1') {
    http_response_code(400);
    echo json_encode(null);
    exit;
}

$query = Security::input('q', 'GET', '');
$default = [
    'img' => Asset::url('images/square.png'),
    'border' => '3px solid rgba(99, 102, 241, 0)',
    'shadow' => '0 0 0 rgba(99, 102, 241, 0)',
];

if ($query === '') {
    echo json_encode($default);
    exit;
}

$conn = Database::connection();
$search = $query . '%';

$stmt = $conn->prepare('SELECT username, avatarURL, permRole FROM users WHERE username LIKE :username ORDER BY id DESC LIMIT 1');
$stmt->bindValue(':username', $search);
$stmt->execute();
$row = $stmt->fetch();

if (!$row) {
    echo json_encode($default);
    exit;
}

$hex = Auth::roleHexColor((int) $row['permRole']);
$img = Asset::url('images/square.png');

if (!empty($row['avatarURL'])) {
    $root = Paths::webRootPath();
    $img = ($root !== '' ? $root : '') . '/profilePictures/' . $row['avatarURL'];
}

$response = [
    'img' => $img,
    'border' => '3px solid ' . $hex,
    'shadow' => '0 0 18px 0 ' . $hex,
];

echo json_encode($response);
