<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_SESSION['loggedIn']['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['discord'] == "" || $row['discord'] == null) {
  echo "0";
} else {
  echo "1";
}
 ?>
