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
date_default_timezone_set('Europe/London');
$date = date('m/d/Y');
$user = $_GET['user'];
$type = $_POST['type'];
$content = $_POST['content'];
$impro = $_POST['impro'];
$stmt = $conn->prepare("SELECT * FROM review_assignments WHERE user = :id AND assigned = :assign ORDER BY id DESC");
$stmt->bindParam(":id", $_SESSION['loggedIn']['id']);
$stmt->bindParam(":assign", $user);
$stmt->execute();
$count = $stmt->rowCount();
if ($count == 0) {
  echo "not assigned";
  exit();
}
$stmt = $conn->prepare("INSERT INTO reviews (user, admin, type, content, impro, times) VALUES (:user, :admin, :type, :content, :impro, :times)");
$stmt->bindParam(':user', $user);
$stmt->bindParam(':admin', $_SESSION['loggedIn']['id']);
$stmt->bindParam(':type', $type);
$stmt->bindParam(':content', $content);
$stmt->bindParam(':impro', $impro);
$stmt->bindParam(':times', $date);
$stmt->execute();
$stmt = $conn->prepare("UPDATE review_assignments SET completed = 1 WHERE user = :id AND assigned = :assign");
$stmt->bindParam(":id", $_SESSION['loggedIn']['id']);
$stmt->bindParam(":assign", $user);
$stmt->execute();
echo "created";
?>
