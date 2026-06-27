<?php

$id = $_SESSION['loggedIn']['id'];
$djSays = $_POST['value'];
$stmt = $conn->prepare("UPDATE users SET djSays = :djsays WHERE id = :id");
$stmt->bindParam(':djsays', $djSays);
$stmt->bindParam(':id', $id);
$stmt->execute();
echo "updated";
?>
