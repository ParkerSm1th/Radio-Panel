<?php

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM redirect WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

echo "deleted";
?>
