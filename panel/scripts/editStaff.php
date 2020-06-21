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
$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
$avatar = '';
date_default_timezone_set('UTC');
$date = date('jS \of F Y');
if ($_POST['radio'] != null) {
  $radio = 1;
  $djSays = $_POST['djSays'];
} else {
  $radio = 0;
  $djSays = null;
}
if ($_POST['media'] != null) {
  $media = 1;
} else {
  $media = 0;
}
if ($_POST['pending'] != null) {
  $pending = 'true';
} else {
  $pending = 'false';
}
if ($_POST['region'] != "EU" && $_POST['region'] != "NA" && $_POST['region'] != "Global" && $_POST['region'] != "OC") {
  echo "error";
  exit();
}
if ($_POST['trial'] != null) {
  $trial = 1;
  $role = "Trial " . $_POST['role'];
} else {
  $trial = 0;
  $role = $_POST['role'];
}
if ($_POST['prole'] == 3) {
  if ($_SESSION['loggedIn']['permRole'] < 4) {
    echo "error";
    exit();
  }
}
if ($_POST['prole'] == 4) {
  if ($_SESSION['loggedIn']['permRole'] < 5) {
    echo "error";
    exit();
  }
}
if ($_POST['prole'] >= 5) {
  if ($_SESSION['loggedIn']['developer'] != 1) {
    echo "error";
    exit();
  }
}
if ($_POST['password'] != null) {
  $update = true;
  $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
} else {
  $update = false;
}
if ($update) {
  $stmt = $conn->prepare("UPDATE users SET username = :username, pass = :pass, permRole = :prole, radio = :radio, media = :media, inactive = :pending, region = :region, trial = :trial, djSays = :djSays WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->bindParam(':username', $_POST['username']);
  $stmt->bindParam(':pass', $pass);
  $stmt->bindParam(':prole', $_POST['prole']);
  $stmt->bindParam(':radio', $radio);
  $stmt->bindParam(':media', $media);
  $stmt->bindParam(':pending', $pending);
  $stmt->bindParam(':region', $_POST['region']);
  $stmt->bindParam(':trial', $trial);
  $stmt->bindParam(':djSays', $djSays);
  $stmt->execute();
  $stmt = $conn->prepare("UPDATE sessions SET refresh = 1 WHERE user = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  echo "updated";
} else {
  $stmt = $conn->prepare("UPDATE users SET username = :username, permRole = :prole, radio = :radio, media = :media, inactive = :pending, region = :region, trial = :trial, djSays = :djSays WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->bindParam(':username', $_POST['username']);
  $stmt->bindParam(':prole', $_POST['prole']);
  $stmt->bindParam(':radio', $radio);
  $stmt->bindParam(':media', $media);
  $stmt->bindParam(':pending', $pending);
  $stmt->bindParam(':region', $_POST['region']);
  $stmt->bindParam(':trial', $trial);
  $stmt->bindParam(':djSays', $djSays);
  $stmt->execute();
  $stmt = $conn->prepare("UPDATE sessions SET refresh = 1 WHERE user = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  echo "updated";
}
?>
