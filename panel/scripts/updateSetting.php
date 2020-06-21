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
$setting = $_GET['setting'];
$value = $_POST['value'];
$stmt = $conn->prepare("UPDATE global SET value = :value WHERE setting = :setting");
$stmt->bindParam(':value', $value);
$stmt->bindParam(':setting', $setting);
$stmt->execute();
echo "updated";
?>
