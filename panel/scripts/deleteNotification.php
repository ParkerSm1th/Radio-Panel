<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_POST['id'];
$stmt = $conn->prepare("DELETE FROM notifications WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
echo "dl";
?>
