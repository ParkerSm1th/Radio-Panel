<?php

use RadioPanel\Core\Auth;

$user = Auth::user();
if ((int) ($user['permRole'] ?? 0) < 4) {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

$query = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
if ($query === '') {
    echo json_encode([]);
    exit;
}

$search = '%' . $query . '%';
$results = [];

$stmt = $conn->prepare('SELECT id, username FROM users WHERE username LIKE :username ORDER BY id LIMIT 20');
$stmt->bindValue(':username', $search);
$stmt->execute();

foreach ($stmt as $row) {
    $results[] = [
        'title' => '<i class="far fa-user"></i> ' . $row['username'],
        'action' => 'loadDropDownProfile(' . (int) $row['id'] . ')',
    ];
}

$stmt = $conn->prepare('SELECT title, url FROM searches WHERE query LIKE :name ORDER BY id LIMIT 20');
$stmt->bindValue(':name', $search);
$stmt->execute();

foreach ($stmt as $row) {
    $results[] = [
        'title' => '<i class="far fa-link"></i> ' . $row['title'],
        'action' => "loadDropDownPage('" . $row['url'] . "')",
    ];
}

echo json_encode($results);
