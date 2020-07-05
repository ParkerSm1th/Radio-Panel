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
$username = $_POST['username'];
$firstCharacter = $username[0];
$avatar = 'default/' . strtoupper($firstCharacter) . 'default.png';
date_default_timezone_set('UTC');
$date = date('jS \of F Y');
if (isset($_POST['radio'])) {
  if ($_POST['radio'] != null) {
    $radio = 1;
  } else {
    $radio = 0;
  }
} else {
  $radio = 0;
}
if (isset($_POST['media'])) {
  if ($_POST['media'] != null) {
    $media = 1;
  } else {
    $media = 0;
  }
} else {
  $media = 0;
}

if (isset($_POST['pending'])) {
  if ($_POST['pending'] != null) {
    $pending = 1;
  } else {
    $pending = 0;
  }
} else {
  $pending = 0;
}
if (isset($_POST['guest'])) {
  if ($_POST['guest'] != null) {
    $guest = 1;
  } else {
    $guest = 0;
  }
} else {
  $guest = 0;
}
if ($_POST['region'] != "EU" && $_POST['region'] != "NA" && $_POST['region'] != "Global" && $_POST['region'] != "OC") {
  echo "error";
  exit();
}
$disabled = "DISABLED";
$stmt = $conn->prepare("INSERT INTO users (username, pass, avatarURL, permRole, displayRole, radio, media, developer, pending, inactive, hired, region, trial, guest) VALUES (:username, :pass, :avatarURL, '1', :displayRole, :radio, :media, '0', :pending, 'false', :hired, :region, '1', :guest)");
$stmt->bindParam(':username', $_POST['username']);
$stmt->bindParam(':pass', $pass);
$stmt->bindParam(':avatarURL', $avatar);
$stmt->bindParam(':displayRole', $disabled);
$stmt->bindParam(':radio', $radio);
$stmt->bindParam(':media', $media);
$stmt->bindParam(':pending', $pending);
$stmt->bindParam(':hired', $date);
$stmt->bindParam(':guest', $guest);
$stmt->bindParam(':region', $_POST['region']);
$stmt->execute();
echo "created";
?>
