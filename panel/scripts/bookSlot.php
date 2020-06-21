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

if ($row['booked'] !== '0') {
  echo "error, it doesnt equal 0 for " . $id;
} else {
  if ($bookType == '1') {
    $stmt = $conn->prepare("UPDATE timetable SET booked = :userid, booked_type = :type WHERE id = :id");
    $stmt->bindParam(':userid', $_SESSION['loggedIn']['id']);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':type', $bookType);
    $stmt->execute();
    echo "covered";
  } else {
    $stmt = $conn->prepare("UPDATE timetable SET booked = :userid WHERE id = :id");
    $stmt->bindParam(':userid', $_SESSION['loggedIn']['id']);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo "booked";
  }
}
?>
