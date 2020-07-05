<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 4 && $_SESSION['loggedIn']['developer'] != '1') {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM sessions WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['user'] == 1) {
  echo "parker";
  exit();
}
$stmt = $conn->prepare("DELETE FROM sessions WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
echo "deleted";
?>
