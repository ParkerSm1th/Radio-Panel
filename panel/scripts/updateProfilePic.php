<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$id = $_SESSION['loggedIn']['id'];
$currentAvatar = $_SESSION['loggedIn']['avatarURL'];
$target_dir = "../../profilePictures/";
if (isset($_FILES)) {
$temp = explode(".", $_FILES["profilePic"]["name"]);
$random = mt_rand(100, 10000);
$newfilename = $id . '-' . $random . '.' . end($temp);
$target_file = $target_dir . $newfilename;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    echo "image";
    $uploadOk = 0;
    exit();
  }
}


if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
  echo "type";
  $uploadOk = 0;
  exit();
}


if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
    if ($currentAvatar !== null) {
      if (file_exists($target_dir . $currentAvatar)) {
        unlink($target_dir . $currentAvatar);
      }
    }
    $stmt = $conn->prepare("UPDATE users SET avatarURL = :url WHERE id = :id");
    $stmt->bindParam(':url', $newfilename);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $_SESSION['loggedIn']['avatarURL'] = $newfilename;
    echo "uploaded";
  } else {
    echo "error";
    exit();
  }
} else {
  echo "no files!";
  exit();
}
 ?>
