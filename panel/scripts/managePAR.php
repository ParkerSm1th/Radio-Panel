<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 2) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
if ($_GET['action'] == "accept") {
  $stmt = $conn->prepare("UPDATE post_away SET status = 1 WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  $stmt = $conn->prepare("SELECT * FROM post_away WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt = $conn->prepare("UPDATE sessions SET refresh = 1 WHERE user = :user");
  $stmt->bindParam(':user', $row['user']);
  $stmt->execute();
  $type = "success";
  $header = "Post Away Request";
  $content = "Your post away request has been accepted! <br><strong>You are now posted away</strong>";
  $icon = "far fa-check";
  $stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon, active) VALUES (:user, :type, :header, :content, :icon, 1)");
  $stmt->bindParam(':user', $row['user']);
  $stmt->bindParam(':type', $type);
  $stmt->bindParam(':header', $header);
  $stmt->bindParam(':content', $content);
  $stmt->bindParam(':icon', $icon);
  $stmt->execute();
  echo "done";
  exit();
}
if ($_GET['action'] == "deny") {
  $stmt = $conn->prepare("UPDATE post_away SET status = 2 WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  $stmt = $conn->prepare("SELECT * FROM post_away WHERE id = :id");
  $stmt->bindParam(':id', $_GET['id']);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $type = "danger";
  $header = "Post Away Request";
  $content = "Your post away request has been denied.";
  $icon = "far fa-times-circle";
  $stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon, active) VALUES (:user, :type, :header, :content, :icon, 1)");
  $stmt->bindParam(':user', $row['user']);
  $stmt->bindParam(':type', $type);
  $stmt->bindParam(':header', $header);
  $stmt->bindParam(':content', $content);
  $stmt->bindParam(':icon', $icon);
  $stmt->execute();
  echo "done";
  exit();
}
?>
