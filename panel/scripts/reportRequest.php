<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['radio'] < 1) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_GET['id'];
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
