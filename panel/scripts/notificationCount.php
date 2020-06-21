<?php
include('../includes/config.php');
session_start();
$stmt = $conn->prepare("SELECT * FROM notifications WHERE userID = :id AND active = '1' ORDER BY id");
$stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
$stmt->execute();
$nCount = $stmt->rowCount();
date_default_timezone_set('Europe/London');
$date = date( 'N' ) - 1;
$id = $_SESSION['loggedIn']['id'];
$hour = date( 'H' );
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND booked = :id AND timestart = :hour");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':day', $date);
$stmt->bindParam(':hour', $hour);
$stmt->execute();
$count = $stmt->rowCount();

$hour = date( 'H' ) + 1;
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND booked = :id AND timestart = :hour");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':day', $date);
$stmt->bindParam(':hour', $hour);
$stmt->execute();
$count2 = $stmt->rowCount();

$stat = $conn->prepare("SELECT * FROM song_log ORDER BY id DESC LIMIT 30");
$stat->execute();
$current = $stat->fetch(PDO::FETCH_ASSOC);
$url = "http://31.220.56.47:3200/stats";
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_GET, true);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$stats = json_decode($result, true);
if ($result == false) {
  echo 'error';
}
if ($stats['success'] == true) {
  $currentDJ = $stats['currentDJ']['id'];
}
if ($count == 1 || $currentDJ == $_SESSION['loggedIn']['id']) {
  $nCount = $nCount + 1;
}
if ($currentDJ != $_SESSION['loggedIn']['id'] && $count == 1) {
  $nCount = $nCount + 1;
} else if ($currentDJ == $_SESSION['loggedIn']['id'] && $count == 0) {
  $nCount = $nCount + 1;
}
if ($count2 == 1) {
  $nCount = $nCount + 1;
}
if ($nCount == 0) {
  echo "";
} else {
  echo '<span class="count" id="notificationsCount">' . $nCount . '</span>';
}
 ?>
