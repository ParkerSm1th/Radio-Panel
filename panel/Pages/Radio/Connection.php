<?php
$perm = 1;
$media = 0;
$radio = 1;
$dev = 0;
$title = "Connection Information";
include('../../includes/header.php');
include('../../includes/config.php');
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM connection_info LIMIT 1");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (false) {
  ?>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Current Connection Information</h4>
      <div class="alert alert-danger text-center" role="alert">
       You've already viewed the connection info! If you need to view it again please contact your Radio Manager.
     </div>
    </div>
  </div>
  <?php
} else {
  $stmt = $conn->prepare("UPDATE users SET viewed_info = 1 WHERE id = :id");
  $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
  $stmt->execute();

  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  $action = "Viewed connection info";
  $log = $conn->prepare("INSERT INTO panel_log (name, ip, times, action) VALUES (:name, :ip, CURRENT_TIMESTAMP, :action)");
  $log->bindParam(':name', $_SESSION['loggedIn']['id']);
  $log->bindParam(':ip', $ip);
  $log->bindParam(':action', $action);
  $log->execute();
 ?>

<style>
input:hover {
 cursor: pointer;
}
 </style>
  <div class="alert alert-danger text-center" role="alert">
     Sharing these connnection details will result in immediate dismissal from the team. If you need help setting up your encoder please contact your radio manager.
   </div>
<div class="card">
  <div class="card-body">
    <form class="forms-sample" action="#">
      <div class="form-group">
        <label for="value">Server Type</label>
        <input type="text" class="form-control" name='value' id="value" readonly value="<?php echo $row['server'];?>">
      </div>
      <div class="form-group">
        <label for="value">Server Address</label>
        <input type="text" class="form-control" name='value' id="value" readonly value="<?php echo $row['ip'];?>">
      </div>
      <div class="form-group">
        <label for="value">Port</label>
        <input type="text" class="form-control" name='value' id="value" readonly value="<?php echo $row['port'];?>">
      </div>
      <div class="form-group">
        <label for="value">Password</label>
        <input type="text" class="form-control" name='value' id="value" readonly value="<?php echo $row['password'];?>">
      </div>
      <div class="form-group">
        <label for="value">Mountpoint</label>
        <input type="text" class="form-control" name='value' id="value" readonly value="/<?php echo $row['mountpoint'];?>">
      </div>
      <h4 style="color: #fff; padding-bottom: 10px;">Metadata</h4>
      <div class="form-group">
        <label for="value">Station Name</label>
        <input type="text" class="form-control" name='value' id="value" readonly value="<?php echo $_SESSION['loggedIn']['username'];?>">
      </div>
      <div class="form-group">
        <label for="value">Station URL</label>
        <input type="text" class="form-control" name='value' id="value" readonly value="<?php echo $row['url'];?>">
      </div>
      <div class="form-group">
        <label for="value">Genre</label>
        <input type="text" class="form-control" name='value' readonly value="<?php echo $_SESSION['loggedIn']['id'];?>">
      </div>
    </form>
  </div>
</div>
<script>
$('input').on('click', function() {
  $(this).select();
});
</script>
<?php } ?>
