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
$user = $_GET['user'];
$stmt = $conn->prepare("DELETE FROM points WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$response->resp = "deleted";
$stmt = $conn->prepare("SELECT * FROM points WHERE user = :id");
$stmt->bindParam(':id', $user);
$stmt->execute();
$count = $stmt->rowCount();
$total = 0;
foreach($stmt as $row) {
  if ($row['type'] == 0) {
    $total = $total + $row['value'];
  } else if ($row['type'] == 1) {
    $total = $total - $row['value'];
  }
}
if ($total > 0) {
  $class = "positive";
  $sign = "+";
} else if ($total < 0) {
  $class = "negative";
}
$response->new = $sign . $total;
$response->class = $class;
$response->margin = (($total * 10) / 2);
$cssMath = (($total * 10) / 2);
if ($total !== 0) {
  $left = 50 + $cssMath;
  $right = 50 - $cssMath;
} else {
  $left = 50;
  $right = 50;
}
$response->left = $left;
$response->right = $right;
echo json_encode($response);
?>
