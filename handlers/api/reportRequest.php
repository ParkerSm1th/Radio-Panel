<?php

use RadioPanel\Core\Auth;

$user = Auth::user();
if ((int) ($user['radio'] ?? 0) < 1) {
  http_response_code(403);
  echo 'forbidden';
  exit;
}

$id = (int) $_GET['id'];
$stmt = $conn->prepare("UPDATE requests SET reported = 1 WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

$stmt = $conn->prepare("SELECT * FROM requests WHERE reported = '0' ORDER BY id DESC");
$stmt->execute();
$count = $stmt->rowCount();
if ($count == 0) {
  echo "empty";
} else {
  echo "reported";
}
?>
