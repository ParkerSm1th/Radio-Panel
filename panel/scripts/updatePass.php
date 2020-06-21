<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_SESSION['loggedIn']['id'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET pass = :pass WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':pass', $pass);
$stmt->execute();
echo "updated";
?>
