<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['developer'] != 1) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$pageOne = $_GET['page1'];
$pageTwo = $_GET['page2'];
if ($pageTwo == "") {
  echo "missing";
  exit();
}
$stmt = $conn->prepare("SELECT * FROM panel_pages WHERE id = :page");
$stmt->bindParam(":page", $pageOne);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$firstPos = $row['position'];

$stmt = $conn->prepare("SELECT * FROM panel_pages WHERE id = :page");
$stmt->bindParam(":page", $pageTwo);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$secondPos = $row['position'];

$stmt = $conn->prepare("UPDATE panel_pages SET position = :pos WHERE id = :page");
$stmt->bindParam(":page", $pageOne);
$stmt->bindParam(":pos", $secondPos);
$stmt->execute();

$stmt = $conn->prepare("UPDATE panel_pages SET position = :pos WHERE id = :page");
$stmt->bindParam(":page", $pageTwo);
$stmt->bindParam(":pos", $firstPos);
$stmt->execute();
echo "swapped";
?>
