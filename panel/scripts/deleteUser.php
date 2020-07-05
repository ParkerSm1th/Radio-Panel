<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 3) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_GET['id'];
$user = $conn->prepare("SELECT * FROM users WHERE id = :id");
$user->bindParam(':id', $id);
$user->execute();
$userData = $user->fetch(PDO::FETCH_ASSOC);
$username = $userData['username'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM timetable WHERE booked = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
  $ip = $_SERVER['REMOTE_ADDR'];
}
$action = "Deleted user " . $username;
$log = $conn->prepare("INSERT INTO panel_log (name, ip, times, action) VALUES (:name, :ip, CURRENT_TIMESTAMP, :action)");
$log->bindParam(':name', $_SESSION['loggedIn']['id']);
$log->bindParam(':ip', $ip);
$log->bindParam(':action', $action);
$log->execute();

echo "deleted";
?>
