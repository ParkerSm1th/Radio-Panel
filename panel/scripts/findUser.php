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
$search = "%" . $_GET['q'] . "%";
$results = array();
$stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE :username ORDER BY id");
$stmt->bindParam(':username', $search);
$stmt->execute();

foreach($stmt as $row) {
  $user->title = '<i class="far fa-user"></i> ' . $row['username'];
  $user->action = "loadDropDownProfile(" . $row['id'] . ")";
  array_push($results, $user);
  $user = null;
}

$stmt = $conn->prepare("SELECT * FROM searches WHERE query LIKE :name ORDER BY id");
$stmt->bindParam(':name', $search);
$stmt->execute();
foreach($stmt as $row) {
  $page->title = '<i class="far fa-link"></i> ' .$row['title'];
  $page->action = "loadDropDownPage('" . $row['url'] . "')";
  array_push($results, $page);
  $page = null;
}
echo json_encode($results);
?>