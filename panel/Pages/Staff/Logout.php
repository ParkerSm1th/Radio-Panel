<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 0;
$allowPost = true;
$title = "Logout";
include('../../includes/header.php');
session_destroy();
 ?>
<div class="card">
  <div class="card-body">
    <h1 class="card-title">Logging Out</h1>

    <p class="card-description">Logging you out of the panel..</p>
  </div>
</div>
<script>
window.location = '../index.php';
</script>
