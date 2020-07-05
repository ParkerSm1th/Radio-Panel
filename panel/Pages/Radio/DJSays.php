<?php
$perm = 1;
$media = 0;
$radio = 1;
$dev = 0;
$pending = 1;
$title = "DJ Says";
include('../../includes/header.php');
include('../../includes/config.php');
$id = $_SESSION['loggedIn']['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$current = $row['djSays'];
 ?>
 <?php
      if ($current == "") {
        ?>
        <div class="alert alert-success text-center" role="alert">
         Hey there! It looks like you don't have a DJ Says set yet! Please remember to keep it appropriate and make sure that it follows the <a href="Radio.Rules" class="web-page text-white">radio rules</a>.
       </div>
        <?php
      } else {
        ?>
        <div class="alert alert-warning text-center" role="alert">
         Please remember to keep your DJ Says appropriate and make sure that it follows the <a href="Radio.Rules" class="web-page text-white">radio rules</a>.
       </div>
        <?php
      }
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
        <input type="text" class="form-control" name='value' id="value" placeholder="Enter New DJ Says" value="<?php echo $current ?>">
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
      console.log(formData);

      if (error) {
        $('#errorFieldOut').fadeIn();
        $('#errorField').html(errorMessage);
        return true;
      }

      $.ajax({
          type: 'POST',
          url: 'scripts/setDJSays.php',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'updated') {
          $('#errorFieldOut').fadeIn();
          $('#error').removeClass('btn-danger');
          $('#error').addClass('btn-success');
          $('#error').html('Success! Your DJ Says has been updated');
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
