<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_SESSION['loggedIn']['id'];
$bio = $_POST['bio'];
$stmt = $conn->prepare("UPDATE users SET bio = :bio WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':bio', $bio);
$stmt->execute();
echo "updated";
?>
