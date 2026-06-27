<?php

use RadioPanel\Core\Auth;

$user = Auth::user();
if ((string) ($user['developer'] ?? '0') !== '1') {
  http_response_code(403);
  echo 'forbidden';
  exit;
}

$page = $_GET['page'];
$action = $_GET['action'];
if ($action == "dev") {
  $stmt = $conn->prepare("SELECT * FROM panel_pages WHERE id = :id");
  $stmt->bindParam(":id", $page);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row['dev'] == 1) {
    $stmt = $conn->prepare("UPDATE panel_pages SET dev = '0' WHERE id = :id");
    $stmt->bindParam(':id', $page);
    $stmt->execute();
    $stmt = $conn->prepare("UPDATE sessions SET refresh = '1'");
    $stmt->bindParam(':id', $page);
    $stmt->execute();
    echo "prod";
  } else {
    $stmt = $conn->prepare("UPDATE panel_pages SET dev = '1' WHERE id = :id");
    $stmt->bindParam(':id', $page);
    $stmt->execute();
    $stmt = $conn->prepare("UPDATE sessions SET refresh = '1'");
    $stmt->bindParam(':id', $page);
    $stmt->execute();
    echo "dev";
  }
}
if ($action == "delete") {
  $stmt = $conn->prepare("DELETE FROM panel_pages WHERE id = :id");
  $stmt->bindParam(":id", $page);
  $stmt->execute();
  echo "deleted";
}
?>
