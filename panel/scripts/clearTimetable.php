<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 4) {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
$stmt = $conn->prepare("UPDATE timetable SET booked = 0, booked_type = 0");
$stmt->execute();
echo "updated";
?>
