<?php
$perm = 2;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Post Away Request";
include('../../includes/header.php');
include('../../includes/config.php');
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM post_away WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$seconds = $row['length'] / 1000;
$return = date("d-m-Y", $seconds);
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(":id", $row['user']);
$stmt->execute();
$userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
if ($userDetails['permRole'] == 1) {
  $color = 'dstaff-text';
}
if ($userDetails['permRole'] == 2) {
  $color = 'sstaff-text';
}
if ($userDetails['permRole'] == 3) {
  $color = 'manager-text';
}
if ($userDetails['permRole'] == 4) {
  $color = 'admin-text';
}

if ($userDetails['permRole'] == 5) {
  $color = 'executive-text';
}

if ($userDetails['permRole'] == 6) {
  $color = 'owner-text';
}
$user = "<span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";
 ?>
<div class="card">
  <div class="card-head">
    <h1><?php echo $user ?>'s Post Away Request</h1>
  </div>
  <div class="card-body">
    <form class="forms-sample" id='editStaff' action="#">
      <div class="form-group">
        <label for="username">Return Date</label>
        <span class="form-control"><?php echo $return ?></span>
      </div>
      <div class="form-group">
        <label for="username">Reason</label>
        <span class="form-control"><?php echo $row['reason'] ?></span>
      </div>
      <div class="form-group">
        <a href="HDJ.PostAway" class="web-page" id='cancel'><button class="btn btn-light">Back to PA Requests</button></a>
        <span id="buttons">
            <a href="#" onclick="accept();" id='accept'><button class="btn btn-success">Accept</button></a>
            <a href="#" onclick="deny();" id='deny'><button class="btn btn-danger">Deny</button></a>
        </span>
      </div>

    </form>
  </div>
</div>
<script>
function deny() {
  $.ajax({
      type: 'GET',
      url: 'scripts/managePAR.php?id=<?php echo $id ?>&action=deny'
  }).done(function(response) {
    if (response == "done") {
      newSuccess("You have denied this post away request!");
      $("#buttons").html(``);
    } else {
      newError("An unknown error occured.");
    }
  });
};

function accept() {
  $.ajax({
      type: 'GET',
      url: 'scripts/managePAR.php?id=<?php echo $id ?>&action=accept'
  }).done(function(response) {
    if (response == "done") {
      newSuccess("You have accepted this post away request!");
      $("#buttons").html(``);
    } else {
      newError("An unknown error occured.");
    }
  });
};
</script>
