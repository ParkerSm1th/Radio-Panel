<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
date_default_timezone_set('Europe/London');
$date = date( 'N' ) - 1;
$currentHour = date( 'H' );
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :start");
$stmt->bindParam(':day', $date);
$stmt->bindParam(':start', $currentHour);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$id = $row['id'];

if ($row['booked'] !== '0') {
  echo "error, it doesnt equal 0 for " . $id;
} else {
  $stmt = $conn->prepare("UPDATE timetable SET booked = :userid, booked_type = 1 WHERE id = :id");
  $stmt->bindParam(':userid', $_SESSION['loggedIn']['id']);
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  echo "covered";
}
?>
