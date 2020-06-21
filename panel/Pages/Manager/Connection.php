<?php
$perm = 3;
$media = 0;
$radio = 1;
$dev = 0;
$title = "Edit Connection Info";
include('../../includes/header.php');
include('../../includes/config.php');
$stmt = $conn->prepare("SELECT * FROM connection_info LIMIT 1");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
 ?>

<div class="card">
  <div class="card-body">
    <div class="alert alert-warning text-center" role="alert">
     Updating the connection info notifies ALL users, pleae be careful when doing this.
   </div>
    <form class="forms-sample" id="editDetails" action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'></span>
      </div>
      <div class="form-group">
        <label for="value">Server Type</label>
        <input type="text" class="form-control" name='server' id="server" value="<?php echo $row['server'];?>">
      </div>
      <div class="form-group">
        <label for="value">Server Address</label>
        <input type="text" class="form-control" name='ip' id="ip" value="<?php echo $row['ip'];?>">
      </div>
      <div class="form-group">
        <label for="value">Port</label>
        <input type="text" class="form-control" name='port' id="port" value="<?php echo $row['port'];?>">
      </div>
      <div class="form-group">
        <label for="value">Password</label>
        <input type="text" class="form-control" name='pass' id="pass" value="<?php echo $row['password'];?>">
      </div>
      <div class="form-group">
        <label for="value">Mountpoint</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text">/</div>
          </div>
          <input type="text" class="form-control" name='mount' id="mount" value="<?php echo $row['mountpoint'];?>">
        </div>
      </div>
      <h4 style="color: #fff; padding-bottom: 10px;">Metadata</h4>
      <div class="form-group">
        <label for="value">Station URL</label>
        <input type="text" class="form-control" name='url' id="url" value="<?php echo $row['url'];?>">
      </div>
      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
var form = $('#editDetails');
$(form).submit(function(event) {
    var error = false;
    var errorMessage = '';
    event.preventDefault();
    var formData = $(form).serialize();
    var server = $('#server');
    var ip = $('#ip');
    var port = $('#port');
    var pass = $('#pass');
    var mount = $('#mount');
    var url = $('#url');
    if (server.val() == "" || server.val() == null || ip.val() == "" || ip.val() == null || port.val() == "" || port.val() == null || pass.val() == "" || pass.val() == null || mount.val() == "" || mount.val() == null || url.val() == "" || url.val() == null) {
      error = true;
      errorMessage = 'Please fill in all fields.';
    }

    if (error) {
      $('#errorFieldOut').fadeIn();
      $('#errorField').html(errorMessage);
      return true;
    }

    $.ajax({
        type: 'POST',
        url: 'scripts/editInfo.php',
        data: formData
    }).done(function(response) {
      if (response == 'updated') {
        $('#errorFieldOut').fadeIn();
        $('#errorField').removeClass('btn-danger');
        $('#errorField').addClass('btn-success');
        $('#errorField').html('Success! Info Updated');
      } else {
        $('#errorField').addClass('btn-danger');
        $('#errorField').removeClass('btn-success');
        $('#errorFieldOut').fadeIn();
        $('#errorField').html('Unknown error occured..');
      }
    }).fail(function (response) {
        $('#errorFieldOut').fadeIn();
        $('#errorField').html('Unknown error occured.');
    });
});
</script>
