<?php

$db_host = "localhost";
$db_user = "keyfm";
$db_pass = "NxsGOH1I6Vm8tsVOAExQoiXoi17FMp";
$db_name = "keyfm";
$conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
date_default_timezone_set("Europe/London");

if (isset($debugScripts)) {
  if ($debugScripts == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  }
}

?>
