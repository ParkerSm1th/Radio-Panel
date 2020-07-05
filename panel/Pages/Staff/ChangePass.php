<?php
  $perm = 1;
  $media = 0;
  $radio = 0;
  $dev = 0;
  $pending = 1;
  $allowPost = true;
  $title = "Change Password";
  include('../../includes/header.php');
  include('../../includes/config.php');
 ?>

<div class="card">
  <div class="card-body">
    <h4 class="card-title">Update Password</h4>
    <form class="forms-sample" id='updatePass' action="#">
      <div class="form-group" id='errorFieldOut2' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField2'></span>
      </div>
      <div class="form-group">
        <label for="value">New Password</label>
        <input type="password" class="form-control" name='pass' id="pass" placeholder="Please enter your new secure password here">
      </div>
      <div class="form-group" style="text-align: center; padding-top: 20px;">
        <button class="btn btn-success mr-2" id='submit'>Update Password</button>
      </div>

    </form>
  </div>
</div>
<script>
var form = $('#updatePass');
$(form).submit(function(event) {
    var error = false;
    var errorMessage = '';
    event.preventDefault();
    var formData = $(form).serialize();
    if ($("#pass").val() == null || $("#pass").val() == "") {
      error = true;
      errorMessage = "Please fill out all of the fields";
    }
    if (error) {
      $('#errorFieldOut2').fadeIn();
      $('#errorField2').html(errorMessage);
      return true;
    }

    $.ajax({
        type: 'POST',
        url: 'scripts/updatePass.php',
        data: formData
    }).done(function(response) {
      console.log(response);
      if (response == 'updated') {
        $('#errorFieldOut2').fadeIn();
        $('#errorField2').removeClass('btn-danger');
        $('#errorField2').addClass('btn-success');
        $('#errorField2').html('Success! Password Updated!');
      } else {
        $('#errorField2').addClass('btn-danger');
        $('#errorField2').removeClass('btn-success');
        $('#errorFieldOut2').fadeIn();
        $('#errorField2').html('Unknown error occured..');
      }
    }).fail(function (response) {
        $('#errorFieldOut2').fadeIn();
        $('#errorField2').html('Unknown error occured.');
    });
  });

</script>
