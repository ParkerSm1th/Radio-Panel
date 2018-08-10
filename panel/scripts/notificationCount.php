<?php
include('../includes/config.php');
session_start();
$stmt = $conn->prepare("SELECT * FROM notifications WHERE userID = :id AND active = '1' ORDER BY id");
$stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
$stmt->execute();
$count = $stmt->rowCount();
echo $count;
 ?>
