<?php

use RadioPanel\Core\Auth;

$user = Auth::user();
if ((int) ($user['permRole'] ?? 0) < 4 && (string) ($user['developer'] ?? '0') !== '1') {
  http_response_code(403);
  echo 'forbidden';
  exit;
}

$id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM sessions WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['user'] == 1) {
  echo "parker";
  exit();
}
$stmt = $conn->prepare("DELETE FROM sessions WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
echo "deleted";
?>
