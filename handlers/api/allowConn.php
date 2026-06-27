<?php

$id = $_GET['id'];
$stmt = $conn->prepare("UPDATE users SET viewed_info = '0' WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

$stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon) VALUES (:id, 'success', 'Connection Info', 'You can now view the connnection info again', 'far fa-list-alt')");
$stmt->bindParam(':id', $id);
$stmt->execute();
echo "updated";
?>
