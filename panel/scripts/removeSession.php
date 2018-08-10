<?php
session_id($_POST['id']);
session_start();
session_destroy();
echo "removed";
 ?>
