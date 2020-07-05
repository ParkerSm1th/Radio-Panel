<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 4) {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
if ($_GET['action'] == "remove") {
  $stmt = $conn->prepare("DELETE FROM banned WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  echo "removed";
  exit();
}
$artist = $_POST['artist'];
$song = $_POST['song'];
if ($song == null) {
  $type = 1;
  $song = '';
} else {
  $type = 0;
}
$stmt = $conn->prepare("INSERT INTO banned (artist, song, banned_by, type) VALUES (:artist, :song, :banned_by, :type)");
$stmt->bindParam(':artist', $artist);
$stmt->bindParam(':song', $song);
$stmt->bindParam(':banned_by', $_SESSION['loggedIn']['id']);
$stmt->bindParam(':type', $type);
$stmt->execute();
echo "added";
?>
