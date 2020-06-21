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
$id = $_POST['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['permRole'] == 3) {
  if ($_SESSION['loggedIn']['permRole'] < 4) {
    echo "error";
    exit();
  }
}
if ($row['permRole'] == 4) {
  if ($_SESSION['loggedIn']['permRole'] < 5) {
    echo "error";
    exit();
  }
}
if ($row['permRole'] >= 5) {
  if ($_SESSION['loggedIn']['developer'] != 1) {
    echo "error";
    exit();
  }
}
$stmt = $conn->prepare("UPDATE users SET trial = '0' WHERE id = :id");
$stmt->bindParam(':id', $_POST['id']);
$stmt->execute();
echo "passed";
?>
