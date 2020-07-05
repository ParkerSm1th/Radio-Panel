<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "View Application";
include('../../includes/header.php');
include('../../includes/config.php');
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM applications WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
 ?>
<div class="card">
  <div class="card-body">
    <h4 class="card-title"><?php echo $row['name'] ?>'s Application</span></h4>
    <form class="forms-sample" id='editStaff' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
      </div>
      <div class="form-group" id='discordMsgOut' style='display: none;'>
        <p style="user-select: auto;"class="btn btn-success submit-btn btn-block" id='discordMessage'>...</p>
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-12">
          <div class="form-group">
            <label for="username">Age</label>
            <span class="form-control"><?php echo $row['age'] ?></span>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="form-group">
            <label for="username">Discord</label>
            <span class="form-control"><?php echo $row['discord'] ?></span>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="form-group">
            <label for="username">Region</label>
            <span class="form-control"><?php echo $row['region'] ?></span>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 col-sm-12">
          <?php
          if ($row['role'] == 0) {
            $role = "<i class='fa fa-microphone-alt'></i> Radio DJ";
            $roleIcon = "microphone-alt";
          } else if ($row['role'] == 1) {
            $role = "<i class='fa fa-newspaper'></i> News Reporter";
          } else if ($row['role'] == 2) {
            $role = "<i class='far fa-share-alt'></i> Media Producer";
          }
           ?>
          <div class="form-group">
            <label for="username">Role</label>
            <span class="form-control"><?php echo $role ?></span>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <?php
          if ($row['mic'] == 0) {
            $microphone = "<i class='fas fa-times'></i>";
          } else if ($row['mic'] == 1) {
            $microphone = "<i class='fas fa-check'></i>";
          }
           ?>
          <div class="form-group">
            <label for="username">Microphone?</label>
            <span class="form-control"><?php echo $microphone ?></span>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <?php
          if ($row['work'] == 0) {
            $job = "<i class='fas fa-times'></i>";
          } else if ($row['work'] == 1) {
            $job = "<i class='fas fa-check'></i>";
          }
           ?>
          <div class="form-group">
            <label for="username">Double Jobbing?</label>
            <span class="form-control"><?php echo $job ?></span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="region">Why?</label>
        <textarea type="text" rows="7" class="form-control" readonly=""><?php echo $row['why']; ?></textarea>
      </div>
      <div class="form-group">
        <a href="Manager.Applications" class="web-page" id='cancel'><button class="btn btn-light">Back to Applications</button></a>
        <span id="buttons">
          <?php
            if ($row['status'] == 1) {
              if ($row['assigned'] == $_SESSION['loggedIn']['id']) {
              ?>
                <a href="#" onclick="unClaim();" id='unclaim'><button class="btn btn-info">Unclaim</button></a>
                <a href="#" onclick="accept();" id='accept'><button class="btn btn-success">Accept</button></a>
                <a href="#" onclick="deny();" id='deny'><button class="btn btn-danger">Deny</button></a>
              <?php
              }
            } else if ($row['status'] == 0) {
              ?>
                <a href="#" onclick="claim();" id='claim'><button class="btn btn-success">Claim</button></a>
                <a href="#" onclick="deny();" id='deny'><button class="btn btn-danger">Deny</button></a>
              <?php
            }
           ?>

        </span>
      </div>

    </form>
  </div>
</div>
<script>
function claim() {
  $.ajax({
      type: 'GET',
      url: 'scripts/editApplication.php?id=<?php echo $id ?>&action=claim'
  }).done(function(response) {
    if (response == "done") {
      newSuccess("You have claimed this application!");
      $("#buttons").html(`
        <a href="#" onclick="unClaim();" id='unclaim'><button class="btn btn-info">Unclaim</button></a>
        <a href="#" onclick="accept();" id='accept'><button class="btn btn-success">Accept</button></a>
        <a href="#" onclick="deny();" id='deny'><button class="btn btn-danger">Deny</button></a>`);
    } else {
      newError("An unknown error occured.");
    }
  });
};

function unClaim() {
  $.ajax({
      type: 'GET',
      url: 'scripts/editApplication.php?id=<?php echo $id ?>&action=unclaim'
  }).done(function(response) {
    if (response == "done") {
      newSuccess("You have unclaimed this application!");
      $("#buttons").html(`
        <a href="#" onclick="claim();" id='claim'><button class="btn btn-success">Claim</button></a>
        <a href="#" onclick="deny();" id='deny'><button class="btn btn-danger">Deny</button></a>`);
    } else {
      newError("An unknown error occured.");
    }
  });
};

function deny() {
  $.ajax({
      type: 'GET',
      url: 'scripts/editApplication.php?id=<?php echo $id ?>&action=deny'
  }).done(function(response) {
    if (response == "done") {
      newSuccess("You have denied this application!");
      urlRoute.loadPage("Manager.Applications")
    } else {
      newError("An unknown error occured.");
    }
  });
};

function accept() {
  $.ajax({
      type: 'GET',
      url: 'scripts/editApplication.php?id=<?php echo $id ?>&action=accept'
  }).done(function(response) {
    if (response == "done") {
      newSuccess("You have accepted this application!");
      $("#buttons").html(`
        <a href="#" onclick="hire();"><button class="btn btn-success">Create Panel Account</button></a>`);
    } else {
      newError("An unknown error occured.");
    }
  });
};

function hire() {
  urlRoute.loadPage("Manager.New?custom=true&name=<?php echo $row['name']?>&role=<?php echo $row['role']?>&region=<?php echo $row['region']?>")
}
</script>
