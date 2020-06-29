<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "New Staff Member";
include('../../includes/header.php');
include('../../includes/config.php');
$min=1000000;
$max=9000000;
$pass = "KeyFM-" . mt_rand($min,$max);
$custom = false;
if ($_GET['custom'] == true) {
  $custom = true;
  $username = $_GET['name'];
  $role = $_GET['role'];
  $region = $_GET['region'];
}
 ?>
<div class="card">
  <div class="card-body">
    <form class="forms-sample" id='newStaff' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
      </div>
      <div class="form-group" id='discordMsgOut' style='display: none;'>
        <p style="user-select: auto;"class="btn btn-success submit-btn btn-block" id='discordMessage'>...</p>
      </div>
      <?php if ($custom) {
        ?>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" name='username' id="username" value="<?php echo $username ?>" placeholder="Enter username">
        </div>
      <?php } else {
        ?>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" name='username' id="username" placeholder="Enter username">
        </div>
        <?php
      }?>

      <div class="form-group">
        <label for="username">Password</label>
        <input type="text" class="form-control" name='password' id="password" placeholder="<?php echo $pass ?>" value="<?php echo $pass ?>">
      </div>
      <p class="card-description">Please select at least one department</p>
      <?php if ($custom) {
        ?>
        <div class="form-group">
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name='radio' id="radio" <?php if ($role == 0) { ?>checked=""<?php } ?> class="form-check-input"> Radio DJ
            <i class="input-helper"></i></label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name='media' id="media" <?php if ($role == 1 || $role == 2) { ?>checked=""<?php } ?> class="form-check-input"> Media Reporter
            <i class="input-helper"></i></label>
          </div>
        </div>
      <?php } else {
        ?>
        <div class="form-group">
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name='radio' id="radio" class="form-check-input"> Radio DJ
            <i class="input-helper"></i></label>
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" name='media' id="media" class="form-check-input"> Media Reporter
            <i class="input-helper"></i></label>
          </div>
        </div>
        <?php
      }?>

      <div class="form-group">
        <label for="region">Region</label>
        <?php if ($custom) {
          ?>
          <select name='region' class="form-control" id="region">
            <option>Global</option>
            <option <?php if ($region == "EU") { ?>selected<?php } ?>>EU</option>
            <option <?php if ($region == "NA") { ?>selected<?php } ?>>NA</option>
            <option <?php if ($region == "OC") { ?>selected<?php } ?>>OC</option>
          </select>
        <?php } else {
          ?>
          <div class="custom-select">
            <select name='region' class="form-control" id="region">
              <option>Global</option>
              <option>EU</option>
              <option>NA</option>
              <option>OC</option>
            </select>
          </div>
          <?php
        }?>

      </div>
      <!--<div class="form-group">
        <label for="region">Display Rank</label>
        <select class="form-control" name='role' id="role">
          <option>Trial Radio DJ</option>
          <option>Trial Media Reporter</option>
        </select>
      </div>-->
      <div class="form-group">
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='pending' id="pending" class="form-check-input" checked=""> Pending
          <i class="input-helper"></i></label>
        </div>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='guest' id="guest" class="form-check-input" checked=""> Guest
          <i class="input-helper"></i></label>
        </div>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='trial' id="trial" class="form-check-input" disabled="" checked=""> Trial
          <i class="input-helper"></i></label>
        </div>
      </div>
      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Submit</button>
        <a href="Manager.New" class="web-page" id='new' style="display: none;"><button class="btn btn-success mr-2">Add Another</button></a>
        <a href="Manager.Staff" class="web-page"><button class="btn btn-light">Cancel</button></a>
      </div>

    </form>
  </div>
</div>
<script>
$(function() {

  var form = $('#newStaff');
  $(form).submit(function(event) {
      var error = false;
      var errorMessage = '';
      event.preventDefault();
      console.log("Submitted");
      var formData = $(form).serialize();
      var username = $('#username');
      var password = $('#password');
      var radio = $('#radio');
      var media = $('#media');
      var region = $('#region');
      var pending = $('#pending');
      var role = $('#role');
      var trial = $('#trial');
      if (password.val() == null || password.val() == "") {
        $("#password")[0].value = "<?php echo $pass ?>";
      }
      if (username.val() == null || username.val() == "" || (radio.val() == '0' && media.val() == '0')) {
        error = true;
        errorMessage = 'Please fill in all fields';
      }

      if (error) {
        $('#errorFieldOut').fadeIn();
        $('#errorField').html(errorMessage);
        return true;
      }

      $.ajax({
          type: 'POST',
          url: 'scripts/newStaff.php',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'created') {
          $('#errorFieldOut').fadeIn();
          $('#errorField').removeClass('btn-danger');
          $('#errorField').addClass('btn-success');
          $('#errorField').html('Success! User Created, please copy and paste the following message to them on discord.');
          $('#discordMsgOut').fadeIn();
          $('#discordMessage').html('Hello! Here are your login details for the KeyFM Staff Panel. Username: **' + username.val() + '** - Password: **' + password.val() + '**' + `
          <br>You can access access the staff panel by heading over to https://staff.keyfm.net
          `);
          $('#submit').hide();
          $('#new').show();
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
});

</script>
