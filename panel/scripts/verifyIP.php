<?php
include('../includes/config.php');
$api = $_POST['api'];
if ($api !== "q1tbDYr9M4rCDM5Nos09Wrg7UlKpSunv9WM3BG9V9N5qeVE") {
  echo "0";
  exit();
}
$id = $_POST['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE discord_id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("UPDATE users SET lastLoginIP = :ip WHERE id = :id");
$stmt->bindParam(':id', $row['id']);
$stmt->bindParam(':ip', $row['newIP']);
$stmt->execute();
echo "1";
?>
