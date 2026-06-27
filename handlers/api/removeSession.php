<?php

require APP_INCLUDES . '/api.php';

use RadioPanel\Core\Auth;
use RadioPanel\Core\Database;
use RadioPanel\Core\Security;

apiRequireLogin();

$user = Auth::user();
$sessionId = Security::input('id', 'POST', '');

if ($sessionId === '' || !preg_match('/^[a-zA-Z0-9,-]+$/', $sessionId)) {
    http_response_code(400);
    echo 'error';
    exit;
}

$conn = Database::connection();
$stmt = $conn->prepare('SELECT user FROM sessions WHERE session = :session LIMIT 1');
$stmt->bindValue(':session', $sessionId);
$stmt->execute();
$row = $stmt->fetch();

if (!$row) {
    echo 'error';
    exit;
}

$ownerId = (int) $row['user'];
$currentId = (int) $user['id'];
$isAdmin = (int) $user['permRole'] >= 4;

if (!$isAdmin && $ownerId !== $currentId) {
    http_response_code(403);
    echo 'forbidden';
    exit;
}

if ($ownerId === $currentId && session_id() === $sessionId) {
    Auth::logout();
    echo 'removed';
    exit;
}

session_id($sessionId);
<?php

session_destroy();

echo 'removed';
