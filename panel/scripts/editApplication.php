<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 3) {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
$id = $_GET['id'];
$action = $_GET['action'];
if ($action == "claim") {
  $stmt = $conn->prepare("UPDATE applications SET status = 1, assigned = :user WHERE id = :id");
  $stmt->bindParam(':user', $_SESSION['loggedIn']['id']);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "done";
} else if ($action == "deny") {
  $stmt = $conn->prepare("UPDATE applications SET status = 3, assigned = :user WHERE id = :id");
  $stmt->bindParam(':user', $_SESSION['loggedIn']['id']);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "done";
} else if ($action == "accept") {
  $stmt = $conn->prepare("UPDATE applications SET status = 2, assigned = :user WHERE id = :id");
  $stmt->bindParam(':user', $_SESSION['loggedIn']['id']);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "done";
} else if ($action == "unclaim") {
  $stmt = $conn->prepare("UPDATE applications SET status = 0, assigned = 0 WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "done";
} else if ($action == "reopen") {
  $stmt = $conn->prepare("UPDATE applications SET status = 0, assigned = 0 WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "done";
}
?>
