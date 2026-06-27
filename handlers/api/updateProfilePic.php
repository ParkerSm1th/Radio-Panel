<?php

use RadioPanel\Core\Auth;
use RadioPanel\Core\Paths;

Auth::requireLogin();

$user = Auth::user();
$id = $user['id'];
$currentAvatar = $user['avatarURL'];
$targetDir = Paths::profilePicturesPath() . '/';

if (!isset($_FILES['profilePic'])) {
    echo 'no files!';
    exit;
}

$temp = explode('.', $_FILES['profilePic']['name']);
$random = mt_rand(100, 10000);
$newfilename = $id . '-' . $random . '.' . end($temp);
$targetFile = $targetDir . $newfilename;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

if (isset($_POST['submit'])) {
    $check = getimagesize($_FILES['profilePic']['tmp_name']);
    if ($check === false) {
        echo 'image';
        exit;
    }
}

if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'], true)) {
    echo 'type';
    exit;
}

if (!move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFile)) {
    echo 'error';
    exit;
}

if ($currentAvatar !== null && strpos($currentAvatar, 'default/') === false) {
    $oldFile = $targetDir . $currentAvatar;
    if (is_file($oldFile)) {
        unlink($oldFile);
    }
}

$stmt = $conn->prepare('UPDATE users SET avatarURL = :url WHERE id = :id');
$stmt->bindParam(':url', $newfilename);
$stmt->bindParam(':id', $id);
$stmt->execute();

$_SESSION['loggedIn']['avatarURL'] = $newfilename;
echo 'uploaded';
