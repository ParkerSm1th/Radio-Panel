<?php

use RadioPanel\Core\Auth;

$user = Auth::user();
$userId = (int) $user['id'];

$stmt = $conn->prepare("SELECT * FROM notifications WHERE userID = :id AND active = '1' ORDER BY id");
$stmt->bindValue(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$nCount = $stmt->rowCount();
date_default_timezone_set('Europe/London');
$date = date( 'N' ) - 1;
$id = $userId;
$hour = date( 'H' );
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND booked = :id AND timestart = :hour");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':day', $date, PDO::PARAM_INT);
$stmt->bindValue(':hour', $hour, PDO::PARAM_INT);
$stmt->execute();
$count = $stmt->rowCount();

$hour = date( 'H' ) + 1;
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND booked = :id AND timestart = :hour");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':day', $date, PDO::PARAM_INT);
$stmt->bindValue(':hour', $hour, PDO::PARAM_INT);
$stmt->execute();
$count2 = $stmt->rowCount();

$stat = $conn->prepare("SELECT * FROM song_log ORDER BY id DESC LIMIT 30");
$stat->execute();
$current = $stat->fetch(PDO::FETCH_ASSOC);
$currentDJ = \RadioPanel\Core\AzuraCast::currentDjId();
if ($count == 1 || $currentDJ == $userId) {
  $nCount = $nCount + 1;
}
if ($currentDJ != $userId && $count == 1) {
  $nCount = $nCount + 1;
} else if ($currentDJ == $userId && $count == 0) {
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
