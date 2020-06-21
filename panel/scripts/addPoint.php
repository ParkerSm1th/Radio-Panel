<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 2) {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
$type = $_POST['type'];
$stmt = $conn->prepare("SELECT * FROM point_types WHERE id = :id");
$stmt->bindParam(":id", $type);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
date_default_timezone_set('UTC');
$date = date('jS \of F Y');
$stmt = $conn->prepare("INSERT INTO points (value, user, issued, title, message, times, type) VALUES (:value, :user, :issued, :title, :message, :times, :type)");
$stmt->bindParam(':value', $row['points']);
$stmt->bindParam(':user', $_POST['user']);
$stmt->bindParam(':issued', $_SESSION['loggedIn']['id']);
$stmt->bindParam(':title', $row['title']);
$stmt->bindParam(':message', $_POST['reason']);
$stmt->bindParam(':times', $date);
$stmt->bindParam(':type', $row['type']);
$stmt->execute();
echo "added";
?>
