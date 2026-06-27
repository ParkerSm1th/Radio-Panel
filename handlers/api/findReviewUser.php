<?php

$search = "%" . $_GET['q'] . "%";
$stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE :username ORDER BY id");
$stmt->bindParam(':username', $search);
$stmt->execute();

$users = array();
foreach($stmt as $row) {
  $stmt = $conn->prepare("SELECT * FROM review_assignments WHERE assigned = :id ORDER BY id");
  $stmt->bindParam(':id', $row['id']);
  $stmt->execute();
  $count = $stmt->rowCount();
  if ($count == 0) {
    $user->name = $row['username'];
    $user->id = $row['id'];
    array_push($users, $user);
  }
  $user = null;
}
echo json_encode($users);
?>