<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_POST['id'];
$stmt = $conn->prepare("SELECT * FROM timetable WHERE id = '$id'");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['booked'] != '0') {
  echo "error, it doesnt equal 0 for " . $id;
} else {
  $stmt = $conn->prepare("UPDATE timetable SET booked = :userid WHERE id = :id");
  $stmt->bindParam(':userid', $_SESSION['loggedIn']['id']);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "booked";
}
?>
