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
} else {
  $radio = 0;
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
$disabled = "DISABLED";
$stmt = $conn->prepare("INSERT INTO users (username, pass, avatarURL, permRole, displayRole, radio, media, developer, inactive, hired, region, trial) VALUES (:username, :pass, :avatarURL, '1', :displayRole, :radio, :media, '0', :inactive, :hired, :region, '1')");
$stmt->bindParam(':username', $_POST['username']);
$stmt->bindParam(':pass', $pass);
$stmt->bindParam(':avatarURL', $avatar);
$stmt->bindParam(':displayRole', $disabled);
$stmt->bindParam(':radio', $radio);
$stmt->bindParam(':media', $media);
$stmt->bindParam(':inactive', $pending);
$stmt->bindParam(':hired', $date);
$stmt->bindParam(':region', $_POST['region']);
$stmt->execute();
echo "created";
?>
