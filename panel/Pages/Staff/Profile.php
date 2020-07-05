<?php
  $perm = 1;
  $media = 0;
  $radio = 0;
  $dev = 0;
  $pending = 1;
  $title = "Profile";
  include('../../includes/header.php');
  include('../../includes/config.php');
  $id = $_SESSION['loggedIn']['id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
 ?>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Profile Picture</h4>
        <form class="forms-sample" id='profilePicture' action="#">
          <div class="form-group" id='errorFieldOut' style='display: none;'>
            <span class="btn btn-danger submit-btn btn-block" id='errorField'></span>
          </div>
          <div class="form-group" id='discordMsgOut' style='display: none;'>
            <p style="user-select: auto;"class="btn btn-success submit-btn btn-block" id='discordMessage'>...</p>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6" style="text-align: center;">
                <p class="profileLabel">Current</p>
                <img id="currentPic" style="border-radius: 100px; height: 150px; width: 150px;" src="../../../profilePictures/<?php echo $row['avatarURL'] ?>" onerror="this.src='../images/square.png'">
              </div>
              <div class="col-md-6">
                <p class="profileLabel">New</p>
                <div class="file-upload">
                  <div class="image-upload-wrap">
                    <input class="file-upload-input" name="profilePic" id="profilePic" type='file' onchange="readURL(this);" accept="image/*" />
                    <div class="drag-text">
                      <h3>Drag and drop a file here</h3>
                    </div>
                  </div>
                  <div class="file-upload-content">
                    <div class="removePic" onclick="removeImage()">
                      <i class="fas fa-times"></i>
                    </div>
                    <img class="file-upload-image" src="#" alt="your image" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group" style="text-align: center; padding-top: 20px;">
            <button class="btn btn-success mr-2" id='submit'>Update Profile Picture</button>
          </div>

        </form>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Profile Details</h4>
        <form class="forms-sample" id='profileDetails' action="#">
          <div class="form-group" id='errorFieldOut2' style='display: none;'>
            <span class="btn btn-danger submit-btn btn-block" id='errorField2'></span>
          </div>
          <div class="form-group">
            <label for="value">Username</label>
            <input type="text" class="form-control" name='username' id="username" readonly value="<?php echo $row['username'];?>">
          </div>
          <div class="form-group">
            <label for="value">Bio</label>
            <textarea type="text" class="form-control" placeholder="Enter a little bit about you :)" rows="6" name='bio' id="bio"><?php echo $row['bio'] ?></textarea>
          </div>
          <div class="form-group" style="text-align: center; padding-top: 20px;">
            <button class="btn btn-success mr-2" id='submit'>Update Profile</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<script>
var newImage = null
function readURL(input) {
  if (input.files && input.files[0]) {

    var reader = new FileReader();

    reader.onload = function(e) {
      $('.image-upload-wrap').hide();

      $('.file-upload-image').attr('src', e.target.result);
      newImage = e.target.result;
      $('.file-upload-content').show();

    };

    reader.readAsDataURL(input.files[0]);

  }
}

function removeImage() {
  $('.image-upload-wrap').show();
  $('.file-upload-content').hide();
  $('.file-upload-image').attr('src', '');
  $("#profilePic").val('');
}

var form = $('#profilePicture');
$(form).submit(function(event) {
    var error = false;
    var errorMessage = '';
    event.preventDefault();
    var formData = new FormData($("#profilePicture")[0]);

    $.ajax({
        type: 'POST',
        url: 'scripts/updateProfilePic.php',
        data: formData,
        processData: false,
        contentType: false
    }).done(function(response) {
      console.log(response);
      if (response == 'uploaded') {
        $('#errorFieldOut').fadeIn();
        $('#errorField').removeClass('btn-danger');
        $('#errorField').addClass('btn-success');
        $('#errorField').html('Success! Profile Pic Updated!');
        $('#currentPic').attr('src', newImage);
        $(".profile-image img").attr('src', newImage);
        $("#UserDropdown img").attr('src', newImage);
        $('.file-upload-content').hide();
        $('.image-upload-wrap').show();
      } else if (response == 'image') {
        $('#errorField').removeClass('btn-success');
        $('#errorField').addClass('btn-danger');
        $('#errorFieldOut').fadeIn();
        $('#errorField').html('Error, that is not an image.');
      } else if (response == 'big') {
        $('#errorField').removeClass('btn-success');
        $('#errorField').addClass('btn-danger');
        $('#errorFieldOut').fadeIn();
        $('#errorField').html('Error, that image is too large, 2M or less please!');
      } else if (response == 'type') {
        $('#errorField').removeClass('btn-success');
        $('#errorField').addClass('btn-danger');
        $('#errorFieldOut').fadeIn();
        $('#errorField').html('Error, invalid type. You can use a PNG, JPG, or JPEG');
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

var form2 = $('#profileDetails');
$(form2).submit(function(event) {
    var error = false;
    var errorMessage = '';
    event.preventDefault();
    var formData = $(form2).serialize();
    if ($("#bio").val() == null || $("#bio").val() == "") {
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
        url: 'scripts/updateProfile.php',
        data: formData
    }).done(function(response) {
      console.log(response);
      if (response == 'updated') {
        $('#errorFieldOut2').fadeIn();
        $('#errorField2').removeClass('btn-danger');
        $('#errorField2').addClass('btn-success');
        $('#errorField2').html('Success! Profile Updated!');
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
