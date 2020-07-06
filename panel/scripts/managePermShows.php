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
$debugScripts = 1;
$user = $_GET['user'];
$assigned = $_GET['assigned'];
if ($_GET['action'] == 'edit') {
  $show = $_GET['show'];
  $name = $_POST['name'];
  $hosts = $_POST['hosts'];
  $day = $_POST['day'];
  $time = $_POST['time'];
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :time");
  $stmt->bindParam(":day", $day);
  $stmt->bindParam(":time", $time);
  $stmt->execute();
  $timeDetails = $stmt->fetch(PDO::FETCH_ASSOC);
  $timeslot = $timeDetails['id'];
  $stmt = $conn->prepare("UPDATE perm_shows SET name = :name, time = :time, hosts = :hosts WHERE id = :id");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':time', $timeslot);
  $stmt->bindParam(':hosts', $hosts);
  $stmt->bindParam(':id', $show);
  $stmt->execute();
  $stmt = $conn->prepare("SELECT * FROM perm_shows WHERE id = :id");
  $stmt->bindParam(':id', $show);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt = $conn->prepare("UPDATE users SET username = :name WHERE id = :id");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':id', $row['user_id']);
  $stmt->execute();
  echo "updated";
  exit();
}
if ($_GET['action'] == 'new') {
  $name = $_POST['name'];
  $hosts = $_POST['hosts'];
  $day = $_POST['day'];
  $time = $_POST['time'];
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :time");
  $stmt->bindParam(":day", $day);
  $stmt->bindParam(":time", $time);
  $stmt->execute();
  $timeDetails = $stmt->fetch(PDO::FETCH_ASSOC);
  $timeslot = $timeDetails['id'];
  $pass = 'null';
  $firstCharacter = $name[0];
  $avatar = 'default/' . strtoupper($firstCharacter) . 'default.png';
  date_default_timezone_set('UTC');
  $date = date('jS \of F Y');
  $disabled = "DISABLED";
  $radio = '0';
  $media = '0';
  $pending = '0';
  $guest = '0';
  $region = 'Global';
  $stmt = $conn->prepare("INSERT INTO users (username, pass, avatarURL, permRole, displayRole, radio, media, developer, pending, inactive, hired, region, trial, guest, type) VALUES (:username, :pass, :avatarURL, '1', :displayRole, :radio, :media, '0', :pending, 'false', :hired, :region, '1', :guest, 1)");
  $stmt->bindParam(':username', $name);
  $stmt->bindParam(':pass', $pass);
  $stmt->bindParam(':avatarURL', $avatar);
  $stmt->bindParam(':displayRole', $disabled);
  $stmt->bindParam(':radio', $radio);
  $stmt->bindParam(':media', $media);
  $stmt->bindParam(':pending', $pending);
  $stmt->bindParam(':hired', $date);
  $stmt->bindParam(':guest', $guest);
  $stmt->bindParam(':region', $region);
  $stmt->execute();
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :name AND type = 1");
  $stmt->bindParam(":name", $name);
  $stmt->execute();
  $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
  $bio = '';
  $stmt = $conn->prepare("INSERT INTO perm_shows (user_id, name, time, hosts, bio, coverURL) VALUES (:user, :name, :time, :hosts,:bio, :coverURL)");
  $stmt->bindParam(':user', $userDetails['id']);
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':time', $timeslot);
  $stmt->bindParam(':hosts', $hosts);
  $stmt->bindParam(':bio', $bio);
  $stmt->bindParam(':coverURL', $bio);
  $stmt->execute();
  echo "created";
  exit();
}
if ($_GET['action'] == 'findUser') {
  $search = "%" . $_GET['q'] . "%";
  $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE :username ORDER BY id");
  $stmt->bindParam(':username', $search);
  $stmt->execute();

  $users = array();
  foreach($stmt as $row) {
    $user->name = $row['username'];
    $user->id = $row['id'];
    array_push($users, $user);
    $user = null;
  }
  echo json_encode($users);
  exit();
}
if ($_GET['action'] == 'get') {
  $stmt = $conn->prepare("SELECT * FROM perm_shows WHERE id = :id ORDER BY id DESC");
  $stmt->bindParam(":id", $_GET['show']);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $hostArr=explode(",", $row['hosts']); 
  $users = array();
  foreach($hostArr as $var) {
    $user = null;
    $id = $row['assigned'];
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = :id");
    $stmt->bindParam(":id", $var);
    $stmt->execute();
    $userDe = $stmt->fetch(PDO::FETCH_ASSOC);
    $user->name = $userDe['username'];
    $user->id = $userDe['id'];
    array_push($users, $user);
  }
  echo json_encode($users);
  exit();
}
?>
