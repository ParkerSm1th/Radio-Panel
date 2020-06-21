<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 4) {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
$user = $_GET['user'];
$assigned = $_GET['assigned'];
if ($_GET['action'] == 'add') {
  $stmt = $conn->prepare("INSERT INTO review_assignments (user, assigned, completed) VALUES (:user, :assigned, 0)");
  $stmt->bindParam(':user', $user);
  $stmt->bindParam(':assigned', $assigned);
  $stmt->execute();
  echo "added";
} 
if ($_GET['action'] == 'del') {
  $stmt = $conn->prepare("DELETE FROM review_assignments WHERE user = :user AND assigned = :assigned");
  $stmt->bindParam(':user', $user);
  $stmt->bindParam(':assigned', $assigned);
  $stmt->execute();
  echo "deleted";
}
if ($_GET['action'] == 'get') {
  $stmt = $conn->prepare("SELECT * FROM review_assignments WHERE user = :user ORDER BY id");
  $stmt->bindParam(':user', $user);
  $stmt->execute();
  $users = array();
  foreach($stmt as $row) {
    $user = null;
    $id = $row['assigned'];
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $userDe = $stmt->fetch(PDO::FETCH_ASSOC);
    $user->name = $userDe['username'];
    $user->id = $userDe['id'];
    array_push($users, $user);
  }
  echo json_encode($users);
}
?>
