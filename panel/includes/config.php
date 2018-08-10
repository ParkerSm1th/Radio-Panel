<?php

$db_host = "localhost";
$db_user = "custom_panel";
$db_pass = "Rit].WVSF2N^";
$db_name = "custom_panel";

$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);


?>
