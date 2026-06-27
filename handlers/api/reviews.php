<?php

if ($_GET['action'] == "publish") {
  $stmt = $conn->prepare("UPDATE reviews SET published = 1");
  $stmt->execute();
  $stmt = $conn->prepare("DELETE FROM review_assignments");
  $stmt->execute();
  echo "updated";
}
?>
