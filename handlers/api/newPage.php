<?php

use RadioPanel\Core\Auth;

$user = Auth::user();
if ((string) ($user['developer'] ?? '0') !== '1') {
  http_response_code(403);
  echo 'forbidden';
  exit;
}

$name = $_POST['name'];
$dev = $_POST['dev'];
$url = $_POST['url'];
$rank = $_POST['rank']; 
if ($dev == "on") {
  $dev = 1;
} else {
  $dev = 0;
}
$stmt = $conn->prepare("SELECT * FROM panel_pages WHERE nav_rank = :rank ORDER BY id DESC");
$stmt->bindParam(":rank", $rank);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$position = $row['position'] + 1;
$stmt = $conn->prepare("INSERT INTO panel_pages (name, url, nav_rank, position, dev) VALUES (:name, :url, :nav_rank, :position, :dev)");
$stmt->bindParam(':name', $name);
$stmt->bindParam(':url', $url);
$stmt->bindParam(':nav_rank', $rank);
$stmt->bindParam(':position', $position);
$stmt->bindParam(':dev', $dev);
$stmt->execute();
echo "added";
?>
