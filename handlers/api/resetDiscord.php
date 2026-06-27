<?php

$id = $_GET['id'];
$stmt = $conn->prepare("UPDATE users SET discord = '', discord_id = '' WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM sessions WHERE user = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
echo "updated";
?>
