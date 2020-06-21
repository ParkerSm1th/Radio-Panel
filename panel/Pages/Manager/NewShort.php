<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "New Short URL";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>

<div class="card">
  <div class="card-body">
    <form class="forms-sample" id="newShort" action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'></span>
      </div>
      <div class="form-group">
        <label for="value">Base URL</label>
        <input type="text" class="form-control" name='url' id="url" placeholder="Enter the URL that the short URL will redirect to.">
      </div>
      <div class="form-group">
        <label for="value">Short URL</label>
        <div class="input-group mb-2">
          <div class="input-group-prepend">
            <div class="input-group-text">https://kfm.ooo/</div>
          </div>
          <input type="text" class="form-control" name='short' id="short" placeholder="Enter the slug here">
        </div>
      </div>
      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Add</button>
      </div>
    </form>
  </div>
</div>

<script>
var form = $('#newShort');
$(form).submit(function(event) {
    var error = false;
    var errorMessage = '';
    event.preventDefault();
    var formData = $(form).serialize();
    var url = $('#url');
    var short = $('#short');
    if (url.val() == "" || url.val() == null || short.val() == "" || short.val() == null) {
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
        url: 'scripts/newShort.php',
        data: formData
    }).done(function(response) {
      if (response == 'added') {
        $('#errorFieldOut').fadeIn();
        $('#errorField').removeClass('btn-danger');
        $('#errorField').addClass('btn-success');
        $('#errorField').html('Success! Short URL added. <a target="_blank" href="https://kfm.ooo/' + short.val() + '">Visit</a>');
      } else if (response == "dupe") {
        $('#errorField').addClass('btn-danger');
        $('#errorField').removeClass('btn-success');
        $('#errorFieldOut').fadeIn();
        $('#errorField').html('A short URL with that slug already exists, please pick a new one..');
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
