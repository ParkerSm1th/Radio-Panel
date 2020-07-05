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
if ($_GET['action'] == "publish") {
  $stmt = $conn->prepare("UPDATE reviews SET published = 1");
  $stmt->execute();
  $stmt = $conn->prepare("DELETE FROM review_assignments");
  $stmt->execute();
  echo "updated";
}
?>
