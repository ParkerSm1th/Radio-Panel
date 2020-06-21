<?php
include('../includes/config.php');
$api = $_POST['api'];
if ($api !== "q1tbDYr9M4rCDM5Nos09Wrg7UlKpSunv9WM3BG9V9N5qeVE") {
  echo "0";
  exit();
}
$song = $_POST['song'];
$artist = $_POST['artist'];
$djID = $_POST['djID'];
$dj = $_POST['dj'];
date_default_timezone_set('Europe/London');
$date = date('m/d/Y h:i:s a');
$stmt = $conn->prepare("INSERT INTO song_log (title, artist, dj, dj_name, times) VALUES (:song, :artist, :djID, :dj, :times)");
$stmt->bindParam(':song', $song);
$stmt->bindParam(':artist', $artist);
$stmt->bindParam(':djID', $djID);
$stmt->bindParam(':dj', $dj);
$stmt->bindParam(':times', $date);
$stmt->execute();
echo "1";
?>
