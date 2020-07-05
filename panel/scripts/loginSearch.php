<?php
include('../includes/config.php');
if (isset($_GET['loginSearch'])) {
  if ($_GET['loginSearch'] == 1) {
    if ($_GET['q'] == null || $_GET['q'] == '') {
      $resp->img = 'images/square.png';
      $resp->border = "3px solid rgba(28, 139, 129, 0)";
      $resp->shadow = "rgba(70, 70, 70) 0px 0px 15px 0px";
      echo json_encode($resp);
      exit();
    }
    $search = $_GET['q'] . "%";
    $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE :username ORDER BY id DESC LIMIT 1");
    $stmt->bindParam(':username', $search);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['avatarURL'] == null || $row['avatarURL'] == '') {
      $resp->img = 'images/square.png';
    } else {
      $resp->img = 'profilePictures/' . $row['avatarURL'];
    }
    if ($row['permRole'] == 1) {
      $hex = "#2989eb";
    }
    if ($row['permRole'] == 2) {
      $hex = "#9a1790";
    }
    if ($row['permRole'] == 3) {
      $hex = "#006729";
    }
    if ($row['permRole'] == 4) {
      $hex = "#e60505";
    }
    if ($row['permRole'] == 5) {
      $hex = "#d08017";
    }
    if ($row['permRole'] == 6) {
      $hex = "rgb(103, 2, 165)";
    }
    if (strcasecmp($row['username'], $_GET['q']) == 0) {
      $resp->border = "4px solid " . $hex;
      $resp->shadow = "0px 0px 15px 0px " . $hex;
    } else {
      $resp->border = "4px solid " . $hex;
      $resp->shadow = "0px 0px 15px 0px " . $hex;
    }
    echo json_encode($resp);
    exit();
  }
}
?>