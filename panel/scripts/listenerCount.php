<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
if ($_GET['type'] == "h") {
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 12");
  $stmt->execute();
  $listeners = array();
  $firstS = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1");
  $firstS->execute();
  $first = $firstS->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $first['count']);
  foreach($stmt as $row) {
    $count = $row['count'];
    array_push($listeners, $count);
  }
  $secondS = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 12");
  $secondS->execute();
  $last = $secondS->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $last['count']);
  echo json_encode(array_reverse($listeners));
}
if ($_GET['type'] == "d") {
  $listeners = array();
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 12");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 24");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 36");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0'ORDER BY id DESC LIMIT 1 OFFSET 48");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 60");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 72");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 84");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 96");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 108");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 120");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 132");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 144");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  array_push($listeners, $row['count']);
  echo json_encode(array_reverse($listeners));
}
if ($_GET['type'] == "w") {
  $listeners = array();
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 288");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 576");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 864");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0'ORDER BY id DESC LIMIT 1 OFFSET 1152");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 1440");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  $stmt = $conn->prepare("SELECT * FROM listeners_logs WHERE count != '0' ORDER BY id DESC LIMIT 1 OFFSET 1728");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  array_push($listeners, $row['count']);
  array_push($listeners, $row['count']);
  echo json_encode(array_reverse($listeners));
}
 ?>
