<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 3) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM redirect WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

echo "deleted";
?>
