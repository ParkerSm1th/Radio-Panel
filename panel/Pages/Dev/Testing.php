<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 1;
$title = "Testing";
include('../../includes/header.php');
include('../../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}


?>
