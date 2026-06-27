<?php

$setting = $_GET['setting'];
$value = $_POST['value'];
$stmt = $conn->prepare("UPDATE global SET value = :value WHERE setting = :setting");
$stmt->bindParam(':value', $value);
$stmt->bindParam(':setting', $setting);
$stmt->execute();
echo "updated";
?>
