<?php
include('../includes/config.php');
$api = $_POST['api'];
if ($api !== "q1tbDYr9M4rCDM5Nos09Wrg7UlKpSunv9WM3BG9V9N5qeVE") {
  echo "0";
  exit();
}
$id = $_POST['user'];
$discordID = $_POST['discord_id'];
$discordUsername = $_POST['discord_username'];
$stmt = $conn->prepare("UPDATE users SET discord = :discord, discord_id = :discordID WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':discord', $discordUsername);
$stmt->bindParam(':discordID', $discordID);
$stmt->execute();
echo "1";
?>
