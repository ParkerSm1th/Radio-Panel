<?php

use RadioPanel\Core\Auth;

$user = Auth::user();
if ((string) ($user['developer'] ?? '0') !== '1') {
  http_response_code(403);
  echo 'forbidden';
  exit;
}

$pageOne = $_GET['page1'];
$pageTwo = $_GET['page2'];
if ($pageTwo == "") {
  echo "missing";
  exit();
}
$stmt = $conn->prepare("SELECT * FROM panel_pages WHERE id = :page");
$stmt->bindParam(":page", $pageOne);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$firstPos = $row['position'];

$stmt = $conn->prepare("SELECT * FROM panel_pages WHERE id = :page");
$stmt->bindParam(":page", $pageTwo);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$secondPos = $row['position'];

$stmt = $conn->prepare("UPDATE panel_pages SET position = :pos WHERE id = :page");
$stmt->bindParam(":page", $pageOne);
$stmt->bindParam(":pos", $secondPos);
$stmt->execute();

$stmt = $conn->prepare("UPDATE panel_pages SET position = :pos WHERE id = :page");
$stmt->bindParam(":page", $pageTwo);
$stmt->bindParam(":pos", $firstPos);
$stmt->execute();
echo "swapped";
?>
