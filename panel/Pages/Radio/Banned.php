<?php
  $perm = 1;
  $media = 0;
  $radio = 1;
  $dev = 0;
  $pending = 1;
  $title = "Banned Songs";
  include('../../includes/header.php');
  include('../../includes/config.php');
 ?>
 <div class="alert alert-danger text-center" role="alert">
     Playing any of the following songs or artists will result in getting negative reputation and might contribute to the outcome of your trial.
   </div>
<div class="card">
  <div class="card-head">
    <h1>Banned Artists</h1>
  </div>
  <div class="card-body">
     <ul class="moved">
       <?php
       $stmt = $conn->prepare("SELECT * FROM banned WHERE type = 1");
       $stmt->execute();
       foreach($stmt as $row) {
        ?>
          <li><?php echo $row['artist']?></li>
        <?php
       }
       ?>
     </ul>
  </div>
</div>
<div class="card bg-alt">
  <div class="card-head">
    <h1>Banned Songs</h1>
  </div>
  <div class="card-body">
     <ul class="moved">
     <?php
       $stmt = $conn->prepare("SELECT * FROM banned WHERE type = 0");
       $stmt->execute();
       foreach($stmt as $row) {
        ?>
          <li><?php echo $row['artist']?> - <?php echo $row['song'] ?></li>
        <?php
       }
       ?>
     </ul>
  </div>
</div>


