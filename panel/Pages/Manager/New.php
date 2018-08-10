<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
include('../../includes/header.php');
include('../../includes/config.php');
$min=1000000;
$max=9000000;
$pass = "Power-" . mt_rand($min,$max);
 ?>
<div class="card">
  <div class="card-body">
    <h4 class="card-title">New Staff Member</h4>
    <form class="forms-sample" id='newStaff' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
      </div>
      <div class="form-group" id='discordMsgOut' style='display: none;'>
        <p style="user-select: auto;"class="btn btn-success submit-btn btn-block" id='discordMessage'>...</p>
      </div>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name='username' id="username" placeholder="Enter username">
      </div>
      <div class="form-group">
        <label for="username">Password</label>
        <input type="text" class="form-control" name='password' id="password" placeholder="<?php echo $pass ?>" value="<?php echo $pass ?>">
      </div>
      <p class="card-description">Please select at least one department</p>
      <div class="form-group">
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='radio' id="radio" class="form-check-input" checked=""> Radio DJ
          <i class="input-helper"></i></label>
        </div>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='media' id="media" class="form-check-input"> Media Reporter
          <i class="input-helper"></i></label>
        </div>
      </div>
      <div class="form-group">
        <label for="region">Region</label>
        <select name='region' class="form-control" id="region">
          <option>Global</option>
          <option>EU</option>
          <option>NA</option>
          <option>OC</option>
        </select>
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
      if (username.val() == null || username.val() == "" || password.val() == null || password.val() == "" || (radio.val() == '0' && media.val() == '0')) {
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
          $('#discordMessage').html('Hello! Here are your login details for the Power Panel. Username: **' + username.val() + '** - Password: **' + password.val() + '**');
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
