<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < 1) {
  header("Location: Staff.Dashboard");
  exit();
}
if ($_SESSION['loggedIn']['social'] !== 1 && $_SESSION['loggedIn']['permRole'] < 3) {
  header("Location: Staff.Dashboard");
  exit();
}
include('../includes/config.php');
date_default_timezone_set('Europe/London');
$date = date('Y-d-m H:i:s');
$contentRaw = $_POST['content'] . "\n\n- " . $_SESSION['loggedIn']['username'];
$tweetID = "null";
$content = strtr($contentRaw, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
$stmt = $conn->prepare("INSERT INTO tweets (user, content, times, twitter_id, deleted) VALUES (:user, :content, :times, :tweet, 0)");
$stmt->bindParam(':content', $content);
$stmt->bindParam(':times', $date);
$stmt->bindParam(':tweet', $tweetID);
$stmt->bindParam(':user', $_SESSION['loggedIn']['id']);
$stmt->execute();
echo "tweeted";
?>
