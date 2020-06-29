<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Edit User";
include('../../includes/header.php');
include('../../includes/config.php');
if ($_GET['id'] == null) {
  ?>
    <script>urlRoute.loadPage("Staff.Dashboard")</script>
  <?php
  exit();
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['username'] == null) {
  ?>
    <script>urlRoute.loadPage("Manager.Staff")</script>
  <?php
  exit();
}
if ($row['permRole'] == 1) {
  $color = 'dstaff-text';
}
if ($row['permRole'] == 2) {
  $color = 'sstaff-text';
}
if ($row['permRole'] == 3) {
  if ($_SESSION['loggedIn']['permRole'] >= 4) {
    $color = 'manager-text';
  } else {
    ?>
      <script>urlRoute.loadPage("Manager.Staff")</script>
    <?php
    exit();
  }
}
if ($row['permRole'] == 4) {
  if ($_SESSION['loggedIn']['permRole'] >= 5) {
    $color = 'admin-text';
  } else {
    ?>
      <script>urlRoute.loadPage("Manager.Staff")</script>
    <?php
    exit();
  }
}

if ($row['permRole'] == 5) {
  if ($_SESSION['loggedIn']['permRole'] >= 6) {
    $color = 'executive-text';
  } else {
    ?>
      <script>urlRoute.loadPage("Manager.Staff")</script>
    <?php
    exit();
  }
}

if ($row['permRole'] == 6 && $_SESSION['loggedIn']['developer'] != 1) {
  ?>
    <script>urlRoute.loadPage("Manager.Staff")</script>
  <?php
  exit();
} else if ($row['permRole'] == 6 && $_SESSION['loggedIn']['developer'] == 1) {
  $color = 'owner-text';
}
 ?>
<div class="card">
  <div class="card-body">
    <h4 class="card-title"><span class="<?php echo $color ?> userLink" onclick="loadProfile(<?php echo $id ?>)"><?php echo $row['username'] ?></span></h4>
    <form class="forms-sample" id='editStaff' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
      </div>
      <div class="form-group" id='discordMsgOut' style='display: none;'>
        <p style="user-select: auto;"class="btn btn-success submit-btn btn-block" id='discordMessage'>...</p>
      </div>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name='username' id="username" placeholder="Enter username" placeholder="<?php echo $row['username'] ?>" value="<?php echo $row['username'] ?>">
      </div>
      <div class="form-group">
        <label for="username">New Password</label>
        <input type="text" class="form-control" name='password' id="password">
      </div>
      <p class="card-description">Please select at least one department</p>
      <div class="form-group">
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='radio' id="radio" class="form-check-input" <?php if ($row['radio'] == 1) { ?>checked=""<?php } ?>> Radio DJ
          <i class="input-helper"></i></label>
        </div>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='media' id="media" class="form-check-input" <?php if ($row['media'] == 1) { ?>checked=""<?php } ?>> Media Reporter
          <i class="input-helper"></i></label>
        </div>
      </div>
      <?php if ($row['radio'] == 1) {
        ?>
        <div class="form-group">
          <label for="username">DJ Says</label>
          <input type="text" class="form-control" name='djSays' value="<?php echo $row['djSays'] ?>" id="djSays">
        </div>
      <?php } ?>
      <div class="form-group">
        <label for="region">Region</label>
        <select name='region' class="form-control" id="region" value="<?php echo $row['region']?>">
          <option <?php if ($row['region'] == "G") echo "selected"?>>Global</option>
          <option <?php if ($row['region'] == "EU") echo "selected"?>>EU</option>
          <option <?php if ($row['region'] == "NA") echo "selected"?>>NA</option>
          <option <?php if ($row['region'] == "OC") echo "selected"?>>OC</option>
        </select>
      </div>
      <div class="form-group">
        <label for="region">Permission Rank</label>
        <select class="form-control" name='prole' id="prole" value="<?php echo $row['permRole']?>">
          <option <?php if ($row['permRole'] == "1") echo "selected"?> value='1'>Department Staff</option>
          <option <?php if ($row['permRole'] == "2") echo "selected"?> value='2'>Senior Staff</option>
          <?php if ($_SESSION['loggedIn']['permRole'] >= 4) {
            ?>
              <option <?php if ($row['permRole'] == "3") echo "selected"?> value='3'>Manager</option>
            <?php
          }
          ?>
          <?php if ($_SESSION['loggedIn']['permRole'] >= 5) {
            ?>
              <option <?php if ($row['permRole'] == "4") echo "selected"?> value='4'>Administrator</option>
            <?php
          }
          ?>
          <?php if ($_SESSION['loggedIn']['permRole'] >= 6) {
            ?>
              <option <?php if ($row['permRole'] == "5") echo "selected"?> value='5'>Executive</option>
            <?php
          }
          ?>
          <?php if ($_SESSION['loggedIn']['developer'] == 1) {
            ?>
              <option <?php if ($row['permRole'] == "6") echo "selected"?> value='6'>Owner</option>
            <?php
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='pending' id="pending" class="form-check-input" <?php if ($row['inactive'] == 'true') { ?>checked=""<?php } ?>> Pending
          <i class="input-helper"></i></label>
        </div>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='guest' id="guest" class="form-check-input" <?php if ($row['guest'] == 1) { ?>checked=""<?php } ?>> Guest
          <i class="input-helper"></i></label>
        </div>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='trial' id="trial" class="form-check-input" <?php if ($row['trial'] == 1) { ?>checked=""<?php } ?>> Trial
          <i class="input-helper"></i></label>
        </div>
      </div>
      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Submit</button>
        <a href="Manager.Staff" class="web-page" id='new' style="display: none;"><button class="btn btn-success mr-2">Back to staff</button></a>
        <a href="Manager.Staff" class="web-page" id='cancel'><button class="btn btn-light">Cancel</button></a>
        <a href="#" id='delete'><button class="btn btn-danger">Delete User</button></a>
      </div>

    </form>
  </div>
</div>
<script>
$(function() {

  var form = $('#editStaff');
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
      var prole = $('#prole');
      var trial = $('#trial');
      var djSays = $('#djSays');
      console.log(prole.val());
      console.log(region.val());
      console.log(role.val());
      console.log(radio.val());
      console.log(media.val());
      console.log(pending.val());
      console.log(trial.val());
      // (role.val() != "Radio DJ" && role.val() != "Media Reporter" && role.val() != "Media Editor" && role.val() != "Head DJ" && role.val() != "Manager" && role.val() != "Administrator" && role.val() != "Owner" && role.val() != "Developer")
      if ((region.val() != "Global" && region.val() != "EU" && region.val() != "NA" && region.val() != "OC") || (prole.val() != "1" && prole.val() != "2" && prole.val() != "3" && prole.val() != "4" && prole.val() != "5" && prole.val() != "6") || username.val() == "" || username.val() == null || (radio.val() == 'off' && media.val() == 'off')) {
        error = true;
        errorMessage = 'Please fill in all fields correctly.';
      }

      if (error) {
        $('#errorFieldOut').fadeIn();
        $('#errorField').html(errorMessage);
        return true;
      }

      $.ajax({
          type: 'POST',
          url: 'scripts/editStaff.php?id=<?php echo $id ?>',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'updated') {
          $('#errorFieldOut').fadeIn();
          $('#errorField').removeClass('btn-danger');
          $('#errorField').addClass('btn-success');
          $('#errorField').html('Success! User updated');

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

$(document).on("click","#delete", function() {
    urlRoute.loadPage("Manager.DeleteUser?id=<?php echo $id ?>")
  });
</script>
