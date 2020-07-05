<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 3) {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
$id = $_GET['id'];
$stmt = $conn->prepare("UPDATE users SET discord = '', discord_id = '' WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM sessions WHERE user = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
echo "updated";
?>
