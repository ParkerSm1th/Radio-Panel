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

$wordDay = date('l');

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
  $ip = $_SERVER['REMOTE_ADDR'];
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
    $name = $_SESSION['loggedIn']['id'];
    $action = "Covered Slot " . $row['timestart'] . ":00 - " . $row['timeend'] . ":00 on " . $wordDay;
    $log = $conn->prepare("INSERT INTO panel_log (name, ip, times, action) VALUES (:name, :ip, CURRENT_TIMESTAMP, :action)");
    $log->bindParam(':name', $name);
    $log->bindParam(':ip', $ip);
    $log->bindParam(':action', $action);
    $log->execute();
    echo "covered";
  } else {
    $stmt = $conn->prepare("UPDATE timetable SET booked = :userid WHERE id = :id");
    $stmt->bindParam(':userid', $_SESSION['loggedIn']['id']);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $name = $_SESSION['loggedIn']['id'];
    $action = "Booked Slot " . $row['timestart'] . ":00 - " . $row['timeend'] . ":00 on " . $wordDay;
    $log = $conn->prepare("INSERT INTO panel_log (name, ip, times, action) VALUES (:name, :ip, CURRENT_TIMESTAMP, :action)");
    $log->bindParam(':name', $name);
    $log->bindParam(':ip', $ip);
    $log->bindParam(':action', $action);
    $log->execute();
    echo "booked";
  }
}
?>
