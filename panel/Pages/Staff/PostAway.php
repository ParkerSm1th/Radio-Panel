<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 0;
$debug = true;
$allowPost = true;
$title = "Post Away";
include('../../includes/header.php');
include('../../includes/config.php');
$id = $_SESSION['loggedIn']['id'];
$stmt = $conn->prepare("SELECT * FROM post_away WHERE user = :id AND (status = 1 OR status = 0)");
$stmt->bindParam(':id', $id);
$stmt->execute();
$count = $stmt->rowCount();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
 ?>
<div class="card m-w-600">
  <div class="card-head">
    <h1>Post Away Request</h1>
  </div>
  <div class="card-body" id="postContent">
    <?php
      if ($count == 0) {
        ?>
          <form class="forms-sample" id='postAway' action="#">
            <div class="form-group" id='errorFieldOut' style='display: none;'>
              <span class="btn btn-danger submit-btn btn-block" id='error'></span>
            </div>
            <div class="form-group" id='discordMsgOut' style='display: none;'>
              <p class="btn btn-success submit-btn btn-block" id='success'></p>
            </div>
            <div class="form-group">
              <label for="value">Return Date</label>
              <p class="card-description" style="font-size: 12px;margin-bottom: 5px;">Please note: Return dates are in GMT</p>
              <input id="return" name="return" class="form-control">
            </div>
            <div class="form-group">
              <label for="value">Reason</label>
              <input type="text" class="form-control" name='reason' id="reason" placeholder="Enter the reason for posting away">
            </div>
            <div class="form-group text-center">
              <button class="btn btn-success mr-2" id='submit'>Submit Request</button>
            </div>
          </form>
        <?php
      } else {
        if ($row['status'] == 1) {
        $now = date('d-m-Y', time());
        $timestampSeconds = $row['length'] / 1000;
        $done = date("d-m-Y", $timestampSeconds);
        $diff = strtotime($now) - strtotime($done); 
        $dateDiff = abs(round($diff / 86400)); 
        ?>
          <div class="alert alert-dark text-center" role="alert">
            <h1>You are currently posted away.</h1>
            <h5>Days Left: <strong><?php echo $dateDiff?></strong></h5>
            <p>Reason: <?php echo $row['reason']?></p>
            <button class="btn btn-danger mt-2" id="returnEarly">Return Early</button>
          </div>
        <?php
        } else {
          ?>
            <div class="alert alert-dark text-center" role="alert">
              <h1>You are have sent in a request to post away</h1>
              <p>Reason: <?php echo $row['reason']?></p>
              <button class="btn btn-danger mt-2" id="cancel">Cancel Request</button>
            </div>
          <?php
        }
      }
    ?>
    
  </div>
</div>
<script>
flatpickr('#return',{
dateFormat: 'Y-m-d',
allowInput: false,
clickOpens: true,
minDate: new Date().fp_incr(1),
enableTime: false, 
enableSeconds: false, 
inline: false,
shorthandCurrentMonth: false,
minuteIncrement: 5,
mode: "single",
prevArrow: '<i class="fas fa-arrow-circle-left"></i>',
nextArrow: '<i class="fas fa-arrow-circle-right"></i>',
parseDate: false,
shorthandCurrentMonth: true,
static: false,
time_24hr: false,
weekNumbers: false,
noCalendar: false 
});
$(function() {

  var form = $('#postAway');
  $(form).submit(function(event) {
      var error = false;
      var errorMessage = '';
      event.preventDefault();
      console.log("Submitted");
      var formData = $(form).serialize();
      var date = $('#return');
      var reason = $('#reason');
      if (date.val() == "" || date.val == null || reason.val() == "" || reason.val() == null) {
        error = true;
        errorMessage = "Please enter a return date & reason.";
      }

      if (error) {
        $('#errorFieldOut').fadeIn();
        $('#error').html(errorMessage);
        return true;
      }

      $.ajax({
          type: 'POST',
          url: 'scripts/postAway.php',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'requestSent') {
          $('#errorFieldOut').fadeOut();
          newSuccess("Your post away request has been sent in.");
          $("#postContent").html(`
            <div class="alert alert-dark text-center" role="alert">
              <h1>You are have sent in a request to post away</h1>
              <p>Reason: ${reason.val()}</p>
              <button class="btn btn-danger mt-2" onclick="cancelRequest();" id="cancel">Cancel Request</button>
            </div>
          `);
        } else {
          $('#errorFieldOut').addClass('btn-danger');
          $('#errorFieldOut').removeClass('btn-success');
          $('#errorFieldOut').fadeIn();
          $('#error').html('Unknown error occured..');
        }
      }).fail(function (response) {
          $('#errorFieldOut').fadeIn();
          $('#errorFieldOut').addClass('btn-danger');
          $('#errorFieldOut').removeClass('btn-success');
          $('#error').html('Unknown error occured.');
      });
    });
});

function cancelRequest() {
  $.ajax({
      type: 'GET',
      url: 'scripts/postAway.php?cancel=1'
  }).done(function(response) {
    console.log(response);
    if (response == 'cancelled') {
      newSuccess("Your post away request has been retracted.");
      urlRoute.loadPage("Staff.PostAway");
    } else {
      newError("An unknown error occured");
    }
  }).fail(function (response) {
    newError("An unknown error occured");
  });
}

$("#cancel").click(function() {
  $.ajax({
      type: 'GET',
      url: 'scripts/postAway.php?cancel=1'
  }).done(function(response) {
    console.log(response);
    if (response == 'cancelled') {
      newSuccess("Your post away request has been retracted.");
      urlRoute.loadPage("Staff.PostAway");
    } else {
      newError("An unknown error occured");
    }
  }).fail(function (response) {
    newError("An unknown error occured");
  });
})

$("#returnEarly").click(function() {
  $.ajax({
      type: 'GET',
      url: 'scripts/postAway.php?return=1'
  }).done(function(response) {
    console.log(response);
    if (response == 'returned') {
      newSuccess("You have returned early!");
      refreshNav();
      urlRoute.loadPage("Staff.PostAway");
    } else {
      newError("An unknown error occured");
    }
  }).fail(function (response) {
    newError("An unknown error occured");
  });
})

</script>
