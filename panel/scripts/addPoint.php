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
if ($_GET['min']) {
  $value = 3;
  $title = "Didn't complete minimums";
  $type = 1;
  date_default_timezone_set('Europe/London');
  $date = date('jS \of F Y');
  $firstday = date('d/m', strtotime("sunday -1 week"));
  $reason = "Week of " . $firstday;
  $stmt = $conn->prepare("INSERT INTO points (value, user, issued, title, message, times, type) VALUES (:value, :user, :issued, :title, :message, :times, :type)");
  $stmt->bindParam(':value', $value);
  $stmt->bindParam(':user', $_GET['user']);
  $stmt->bindParam(':issued', $_SESSION['loggedIn']['id']);
  $stmt->bindParam(':title', $title);
  $stmt->bindParam(':message', $reason);
  $stmt->bindParam(':times', $date);
  $stmt->bindParam(':type', $type);
  $stmt->execute();
  $type = "danger";
  $header = "New Point";
  $content = "You've been given a negative point!";
  $icon = "far fa-exclamation-circle";
  $stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon, active) VALUES (:user, :type, :header, :content, :icon, 1)");
  $stmt->bindParam(':user', $_GET['user']);
  $stmt->bindParam(':type', $type);
  $stmt->bindParam(':header', $header);
  $stmt->bindParam(':content', $content);
  $stmt->bindParam(':icon', $icon);
  $stmt->execute();
  echo "added";
  exit();
}
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
if ($row['type'] == 0) {
  $type = "success";
  $header = "New Point";
  $content = "You've been given a positive point!";
  $icon = "fas fa-smile-plus";
  $stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon, active) VALUES (:user, :type, :header, :content, :icon, 1)");
  $stmt->bindParam(':user', $_POST['user']);
  $stmt->bindParam(':type', $type);
  $stmt->bindParam(':header', $header);
  $stmt->bindParam(':content', $content);
  $stmt->bindParam(':icon', $icon);
  $stmt->execute();
} else if ($row['type'] == 1) {
  $type = "danger";
  $header = "New Point";
  $content = "You've been given a negative point!";
  $icon = "far fa-exclamation-circle";
  $stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon, active) VALUES (:user, :type, :header, :content, :icon, 1)");
  $stmt->bindParam(':user', $_POST['user']);
  $stmt->bindParam(':type', $type);
  $stmt->bindParam(':header', $header);
  $stmt->bindParam(':content', $content);
  $stmt->bindParam(':icon', $icon);
  $stmt->execute();
} else {
  $type = "warning";
  $header = "New Point";
  $content = "You've been given a neutral point!";
  $icon = "far fa-exclamation-circle";
  $stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon, active) VALUES (:user, :type, :header, :content, :icon, 1)");
  $stmt->bindParam(':user', $_POST['user']);
  $stmt->bindParam(':type', $type);
  $stmt->bindParam(':header', $header);
  $stmt->bindParam(':content', $content);
  $stmt->bindParam(':icon', $icon);
  $stmt->execute();
}
echo "added";
?>
