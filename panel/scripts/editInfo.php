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
$stmt = $conn->prepare("UPDATE connection_info SET ip = :ip, port = :port, password = :password, mountpoint = :mount, server = :server, url = :url WHERE id = '1'");
$stmt->bindParam(':ip', $_POST['ip']);
$stmt->bindParam(':port', $_POST['port']);
$stmt->bindParam(':password', $_POST['pass']);
$stmt->bindParam(':mount', $_POST['mount']);
$stmt->bindParam(':server', $_POST['server']);
$stmt->bindParam(':url', $_POST['url']);
$stmt->execute();
$stmt = $conn->prepare("UPDATE users SET viewed_info = 0");
$stmt->execute();

$stmt = $conn->prepare("SELECT * FROM users WHERE radio = '1' ORDER BY id");
$stmt->execute();

foreach($stmt as $row) {
  $id = $row['id'];
  $stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon) VALUES (:id, 'success', 'Connection Info Updated', 'You can now view the connnection info again', 'far fa-list-alt')");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
}
echo "updated";
?>
