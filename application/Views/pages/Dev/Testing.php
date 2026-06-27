<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 1;
$title = "Testing";
require APP_INCLUDES . '/page_header.php';

$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}


?>
