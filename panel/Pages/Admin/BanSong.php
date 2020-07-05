<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Ban a Song";
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
        <label for="value">Artist</label>
        <input type="text" class="form-control" name='artist' id="artist" placeholder="Enter the Artist Name">
      </div>
      <?php
      if ($_GET['type'] !== 'artist') {
        ?>
          <div class="form-group">
            <label for="value">Song</label>
            <input type="text" class="form-control" name='song' id="song" placeholder="Enter the Song Name">
          </div>
        <?php
      }?>
      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Ban</button>
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
    var artist = $('#artist');
    var song = $('#song');
    if (artist.val() == "" || artist.val() == null) {
      error = true;
      errorMessage = 'Please fill in all fields.';
    }
    <?php
      if ($_GET['type'] !== 'artist') {
        ?>
        if (song.val() == "" || song.val == null) {
          error = true;
          errorMessage = 'Please fill in all fields.';
        }
        <?php
      }
    ?>

    if (error) {
      $('#errorFieldOut').fadeIn();
      $('#errorField').html(errorMessage);
      return true;
    }

    $.ajax({
        type: 'POST',
        url: 'scripts/banSong.php',
        data: formData
    }).done(function(response) {
      console.log(response);
      if (response == 'added') {
        $('#errorFieldOut').fadeOut();
        newSuccess("Success! Added to the banned songs list.");
        urlRoute.loadPage("Admin.BannedSongs");
      } else {
        $('#errorFieldOut').fadeOut();
        newError('Unknown error occured..');
      }
    }).fail(function (response) {
      $('#errorFieldOut').fadeOut();
        newError('Unknown error occured..');
    });
});
</script>
