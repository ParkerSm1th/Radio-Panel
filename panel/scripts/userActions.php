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
if ($_GET['action'] == 'logout') {
  $stmt = $conn->prepare("DELETE FROM sessions WHERE user = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "updated";
} else if ($_GET['action'] == 'reauth') {
  $stmt = $conn->prepare("UPDATE users SET lastLoginIP = '' WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  $stmt = $conn->prepare("DELETE FROM sessions WHERE user = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "updated";
} else if ($_GET['action'] == 'suspend') {
  $stmt = $conn->prepare("UPDATE users SET inactive = 'true' WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  $stmt = $conn->prepare("DELETE FROM sessions WHERE user = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "updated";
}
?>
