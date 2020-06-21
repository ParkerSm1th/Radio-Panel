<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_POST['id'];
$stmt = $conn->prepare("SELECT * FROM timetable WHERE id = '$id'");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

date_default_timezone_set('Europe/London');
$date = date( 'N' ) - 1;
$currentHour = date( 'H' );
if ($row['timestart'] == $currentHour && $row['day'] == $date) {
  $bookType = '1';
} else {
  $bookType = '0';
}

if ($row['booked'] == '0') {
  echo "error, it equals 0 for " . $id;
} else {
  if ($_SESSION['loggedIn']['id'] != $row['booked'] && $_SESSION['loggedIn']['permRole'] < 3) {
    echo "error";
  } else {
    $stmt = $conn->prepare("UPDATE timetable SET booked = '0', booked_type = '0' WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if ($bookType == '1') {
      echo "current";
    } else {
      echo "unbooked";
    }
  }
}
?>
