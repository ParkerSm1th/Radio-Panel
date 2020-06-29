<?php
  $perm = 2;
  $media = 0;
  $radio = 0;
  $dev = 0;
  $title = "New Review";
  include('../../includes/header.php');
  include('../../includes/config.php');
  $id = $_GET['id'];
  $stmt = $conn->prepare("SELECT * FROM post_away WHERE user = :id AND (status = 1 OR status = 0)");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $count = $stmt->rowCount();
  if ($count == 0) {
    $away = '<i class="fa fa-times"></i>';
  } else {
    $away = '<i class="fa fa-check"></i>';
  }
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id AND booked_type = 0");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $booked = $stmt->rowCount();
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id AND booked_type = 1");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $covered = $stmt->rowCount();
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $total = $stmt->rowCount();
?>
<style>
    .rating i {
      float: right;
      padding-left: 5px;
    }
    .rating i:hover {
      color: #fff900;
    }

    .rating i:hover ~ i {
      color: #fff900;
    }
    .bigStat.bg-darker {
      background: #0d1f33;
      height: 71px;
      width: 100%;
      margin-bottom: 20px;
      border-radius: 3px;
    }
    .bigStat p {
      color: #fff;
      padding-top: 1px;
      /* text-align: center; */
      padding-left: 10px;
      margin-bottom: 0px;
      font-size: 34px;
    }
    .bigStat p i {
      padding-top: 6px;
      margin-bottom: 12px;
    }
    .bigStat h1 {
      color: #ffffffb3;
      font-size: 14px;
      padding: 10px;
      margin-bottom: 0px;
      margin-top: -12px;
    }
</style>
<div class="card" style="margin-bottom: 20px;">
  <div class="card-head">
    <h1>New Review for <?php echo getUserSpan($_GET['id'])?></h1>
    <div class="card-actions">
      <a href="HDJ.Reviews" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">Back</button>
      </a>
      <a id="submit">
        <button class="profile-close-button btn btn-light mr-2">Submit Review</button>
      </a>
    </div>
  </div>
  <div class="card-body">
    
    <div class="row">
      <div class="col-md-3 col-sm-12">
        <div class="bigStat bg-darker">
          <p><?php echo $away ?></p>
          <h1>Posted Away</h1>
        </div>
      </div>
      <div class="col-md-3 col-sm-12">
        <div class="bigStat bg-darker">
          <p><?php echo $booked ?></p>
          <h1>Booked Slots</h1>
        </div>
      </div>
      <div class="col-md-3 col-sm-12">
        <div class="bigStat bg-darker">
          <p><?php echo $covered ?></p>
          <h1>Covered Slots</h1>
        </div>
      </div>
      <div class="col-md-3 col-sm-12">
        <div class="bigStat bg-darker">
          <p><?php echo $total ?></p>
          <h1>Completed Slots</h1>
        </div>
      </div>
    </div>
    
    <form class="forms-sample" id='newReview' action="#">
      <div class="review">
        <div class="review-header goldReview" id="RH">
          <div class="type">
            <h1>
            <select name='type' class="form-control inline-select" id="type">
              <option value="0">Grey</option>
              <option value="1">Red</option>
              <option value="2">Blue</option>
              <option value="3">Green</option>
              <option selected value="4">Gold</option>
            </select> Review</h1>
          </div>
          <div class="date">
            <?php
              date_default_timezone_set('Europe/London');
              $date = date('m/d/Y');
            ?>
            <p><?php echo $date ?></p>
          </div>
          <div class="rating" id="rating">
            <i onclick="updateReview('4')" class="fas fa-star"></i>
            <i onclick="updateReview('3')" class="fas fa-star"></i>
            <i onclick="updateReview('2')" class="fas fa-star"></i>
            <i onclick="updateReview('1')" class="fas fa-star"></i>
            <i onclick="updateReview('0')" class="fas fa-star"></i>
          </div>
        </div>
        <div class="content">
          <div class="sec">Overall</div>
          <textarea type="text" rows="3" name="content" id="revContent" class="inline-textarea form-control" placeholder="Please enter the review content here"></textarea>
        </div>
        <div class="impro">
          <div class="sec">Improvement</div>
          <textarea type="text" rows="3" name="impro" id="impro" class="inline-textarea form-control" placeholder="Please enter how they can improve here"></textarea>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
function updateReview(newReview) {
  switch(newReview) {
    case "0":
      $("#RH").css("background", "#a7a7a78a");
      $(".impro").fadeOut();
      $("#revContent").html("You have been posted away this week, a review hasn't been written for you.");
      $("#rating").html(``);
      type.value = 0;
      break;
    case "1":
      $("#RH").css("background", "#dc192b");
      $(".impro").fadeIn();
      $("#revContent").html("");
      $("#rating").html(`
          <i onclick="updateReview('4')" class="fal fa-star"></i>
          <i onclick="updateReview('3')" class="fal fa-star"></i>
          <i onclick="updateReview('2')" class="fal fa-star"></i>
          <i onclick="updateReview('1')" class="fas fa-star"></i>
          <i onclick="updateReview('0')" class="fas fa-star"></i>
      `);
      type.value = 1;
      break;
    case "2":
      $("#RH").css("background", "#00a6c1");
      $(".impro").fadeIn();
      $("#revContent").html("");
      $("#rating").html(`
          <i onclick="updateReview('4')" class="fal fa-star"></i>
          <i onclick="updateReview('3')" class="fal fa-star"></i>
          <i onclick="updateReview('2')" class="fas fa-star"></i>
          <i onclick="updateReview('1')" class="fas fa-star"></i>
          <i onclick="updateReview('0')" class="fas fa-star"></i>
      `);
      type.value = 2;
      break;
    case "3":
      $("#RH").css("background", "#059c00");
      $(".impro").fadeIn();
      $("#revContent").html("");
      $("#rating").html(`
          <i onclick="updateReview('4')" class="fal fa-star"></i>
          <i onclick="updateReview('3')" class="fas fa-star"></i>
          <i onclick="updateReview('2')" class="fas fa-star"></i>
          <i onclick="updateReview('1')" class="fas fa-star"></i>
          <i onclick="updateReview('0')" class="fas fa-star"></i>
      `);
      type.value = 3;
      break;
    case "4":
      $("#RH").css("background", "#948303");
      $(".impro").fadeIn();
      $("#revContent").html("");
      $("#rating").html(`
          <i onclick="updateReview('4')" class="fas fa-star"></i>
          <i onclick="updateReview('3')" class="fas fa-star"></i>
          <i onclick="updateReview('2')" class="fas fa-star"></i>
          <i onclick="updateReview('1')" class="fas fa-star"></i>
          <i onclick="updateReview('0')" class="fas fa-star"></i>
      `);
      type.value = 4;
      break;
    default:
  }
}
$("#type").change(function() {
  var type = $("#type");
  switch(type.val()) {
    case "0":
      $("#RH").css("background", "#a7a7a78a");
      $(".impro").fadeOut();
      $("#revContent").html("You have been posted away this week, a review hasn't been written for you.");
      $("#rating").html(``);
      break;
    case "1":
      $("#RH").css("background", "#dc192b");
      $(".impro").fadeIn();
      $("#revContent").html("");
      $("#rating").html(`
          <i onclick="updateReview('4')" class="fal fa-star"></i>
          <i onclick="updateReview('3')" class="fal fa-star"></i>
          <i onclick="updateReview('2')" class="fal fa-star"></i>
          <i onclick="updateReview('1')" class="fas fa-star"></i>
          <i onclick="updateReview('0')" class="fas fa-star"></i>
      `);
      break;
    case "2":
      $("#RH").css("background", "#00a6c1");
      $(".impro").fadeIn();
      $("#revContent").html("");
      $("#rating").html(`
          <i onclick="updateReview('4')" class="fal fa-star"></i>
          <i onclick="updateReview('3')" class="fal fa-star"></i>
          <i onclick="updateReview('2')" class="fas fa-star"></i>
          <i onclick="updateReview('1')" class="fas fa-star"></i>
          <i onclick="updateReview('0')" class="fas fa-star"></i>
      `);
      break;
    case "3":
      $("#RH").css("background", "#059c00");
      $(".impro").fadeIn();
      $("#revContent").html("");
      $("#rating").html(`
          <i onclick="updateReview('4')" class="fal fa-star"></i>
          <i onclick="updateReview('3')" class="fas fa-star"></i>
          <i onclick="updateReview('2')" class="fas fa-star"></i>
          <i onclick="updateReview('1')" class="fas fa-star"></i>
          <i onclick="updateReview('0')" class="fas fa-star"></i>
      `);
      break;
    case "4":
      $("#RH").css("background", "#948303");
      $(".impro").fadeIn();
      $("#revContent").html("");
      $("#rating").html(`
          <i onclick="updateReview('4')" class="fas fa-star"></i>
          <i onclick="updateReview('3')" class="fas fa-star"></i>
          <i onclick="updateReview('2')" class="fas fa-star"></i>
          <i onclick="updateReview('1')" class="fas fa-star"></i>
          <i onclick="updateReview('0')" class="fas fa-star"></i>
      `);
      break;
    default:
  }
});

$(function() {

var form = $('#newReview');
$("#submit").click(function(event) {
    var error = false;
    var errorMessage = '';
    event.preventDefault();
    console.log("Submitted");
    var formData = $(form).serialize();
    var content = $('#revContent');
    var impro = $('#impro');
    var type = $('#type');
    if (content.val() == null || content.val() == "") {
      error = true;
      errorMessage = 'Please fill in all fields!';
    }

    if (type.val() != 0) {
      if (impro.val() == null || impro.val() == "") {
        error = true;
        errorMessage = 'Please fill in all fields!';
      }
    }

    if (error) {
      newError(errorMessage);
      return true;
    }

    $.ajax({
        type: 'POST',
        url: 'scripts/newReview.php?user=<?php echo $_GET['id']?>',
        data: formData
    }).done(function(response) {
      console.log(response);
      if (response == 'created') {
        newSuccess("Review Submitted!");
        urlRoute.loadPage("HDJ.Reviews");
      } else if (response == 'not assigned') {
        newError("You can not submit a review that is not assigned to you.");
      } else {
        newError("Unknown error occured");
      }
    }).fail(function (response) {
      newError("Unknown error occured");
    });
  });
});
</script>