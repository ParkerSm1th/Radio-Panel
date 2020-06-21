<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 3) {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
$url = $_POST['url'];
$short = $_POST['short'];
$stmt = $conn->prepare("SELECT * FROM redirect WHERE slug = :slug");
$stmt->bindParam(":slug", $short);
$stmt->execute();
$count = $stmt->rowCount();
if ($count !== 0) {
  echo "dupe";
  exit();
}
date_default_timezone_set('UTC');
$date = date('jS \of F Y');
$stmt = $conn->prepare("INSERT INTO redirect (url, slug, date) VALUES (:url, :slug, :date)");
$stmt->bindParam(':url', $url);
$stmt->bindParam(':slug', $short);
$stmt->bindParam(':date', $date);
$stmt->execute();
echo "added";
?>
