<?php
include('../includes/config.php');
session_start();
$id = $_SESSION['discordUserID'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['lastLoginIP'] == $row['newIP']) {
  echo "1";
} else {
  echo "0";
}
 ?>
