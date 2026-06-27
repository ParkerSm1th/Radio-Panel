<?php

$id = $_POST['id'];
$stmt = $conn->prepare("DELETE FROM notifications WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
echo "dl";
?>
