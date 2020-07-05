<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
if ($_GET['cancel'] == 1) {
  $stmt = $conn->prepare("DELETE FROM post_away WHERE user = :user ORDER BY id DESC LIMIT 1");
  $stmt->bindParam(':user', $_SESSION['loggedIn']['id']);
  $stmt->execute();
  echo "cancelled";
  exit();
}
if ($_GET['return'] == 1) {
  $stmt = $conn->prepare("UPDATE post_away SET status = 3 WHERE user = :user ORDER BY id DESC LIMIT 1");
  $stmt->bindParam(':user', $_SESSION['loggedIn']['id']);
  $stmt->execute();
  echo "returned";
  exit();
}
if ($_GET['returnOther'] == 1) {
  $stmt = $conn->prepare("UPDATE post_away SET status = 3 WHERE user = :user ORDER BY id DESC LIMIT 1");
  $stmt->bindParam(':user', $_GET['user']);
  $stmt->execute();
  echo "returned";
  exit();
}
$reason = $_POST['reason'];
$date = $_POST['return'];
$return = (strtotime($date) * 1000);
$current = round(microtime(true) * 1000);
$stmt = $conn->prepare("INSERT INTO post_away (user, reason, length, status, times) VALUES (:user, :reason, :length, 0, :times)");
$stmt->bindParam(':user', $_SESSION['loggedIn']['id']);
$stmt->bindParam(':reason', $reason);
$stmt->bindParam(':length', $return);
$stmt->bindParam(':times', $current);
$stmt->execute();
$url = "http://45.82.72.86:3201/api/keyfm/newForm";
$fields = [
    'api' => "q1tbDYr9M4rCDM5Nos09Wrg7UlKpSunv9WM3BG9V9N5qeVE",
    'username' => $_SESSION['loggedIn']['username'],
    'type'=> 2
];

$fields_string = http_build_query($fields);

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
if ($_SESSION['loggedIn']['id'] != 1) {
  $result = curl_exec($ch);
}
echo "requestSent";
?>
