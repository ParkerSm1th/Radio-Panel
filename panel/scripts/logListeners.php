<?php
include('../includes/config.php');
$api = $_POST['api'];
if ($api !== "q1tbDYr9M4rCDM5Nos09Wrg7UlKpSunv9WM3BG9V9N5qeVE") {
  echo "0";
  exit();
}
$count = $_POST['count'];
date_default_timezone_set('Europe/London');
$timer = round(microtime(true) * 1000);
$stmt = $conn->prepare("INSERT INTO listeners_logs (times, count) VALUES (:times, :count)");
$stmt->bindParam(':times', $timer);
$stmt->bindParam(':count', $count);
$stmt->execute();
echo "1";
?>
