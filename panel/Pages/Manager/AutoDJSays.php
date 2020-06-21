<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Auto DJ Says";
include('../../includes/header.php');
include('../../includes/config.php');
$stmt = $conn->prepare("SELECT * FROM global WHERE setting = 'autoDJ_says'");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$currentValue = $row['value'];
 ?>
<div class="card">
  <div class="card-body">
    <form class="forms-sample" id='editSetting' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='error'></span>
      </div>
      <div class="form-group" id='discordMsgOut' style='display: none;'>
        <p class="btn btn-success submit-btn btn-block" id='success'></p>
      </div>
      <div class="form-group">
        <label for="value">DJ Says</label>
        <input type="text" class="form-control" name='value' id="value" placeholder="Enter New DJ Says" value="<?php echo $currentValue ?>">
      </div>
      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Submit</button>
      </div>
    </form>
  </div>
</div>
<script>
$(function() {

  var form = $('#editSetting');
  $(form).submit(function(event) {
      var error = false;
      var errorMessage = '';
      event.preventDefault();
      console.log("Submitted");
      var formData = $(form).serialize();
      var value = $('#value');

      if (error) {
        $('#errorFieldOut').fadeIn();
        $('#errorField').html(errorMessage);
        return true;
      }

      $.ajax({
          type: 'POST',
          url: 'scripts/updateSetting.php?setting=autoDJ_says',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'updated') {
          $('#errorFieldOut').fadeIn();
          $('#error').removeClass('btn-danger');
          $('#error').addClass('btn-success');
          $('#error').html('Success! The AutoDJs DJ Says has been updated');
        } else {
          $('#error').addClass('btn-danger');
          $('#error').removeClass('btn-success');
          $('#errorFieldOut').fadeIn();
          $('#error').html('Unknown error occured..');
        }
      }).fail(function (response) {
          $('#errorFieldOut').fadeIn();
          $('#error').html('Unknown error occured.');
      });
    });
});

</script>
