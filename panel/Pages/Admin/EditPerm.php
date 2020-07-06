<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Edit Perm Show";
include('../../includes/header.php');
include('../../includes/config.php');
if ($_GET['id'] == null) {
  ?>
    <script>urlRoute.loadPage("Staff.Dashboard")</script>
  <?php
  exit();
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM perm_shows WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $conn->prepare("SELECT * FROM timetable WHERE id = :id");
$stmt->bindParam(":id", $row['time']);
$stmt->execute();
$timeDetails = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['name'] == null) {
  ?>
    <script>urlRoute.loadPage("Admin.Shows")</script>
  <?php
  exit();
}
 ?>
 <style>
   ul.token-input-list-mac {
     margin: 0;
   }
 </style>
<div class="card">
  <div class="card-body">
    <h4 class="card-title"><?php echo $row['name']?></h4>
    <form class="forms-sample" id='editShow' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
      </div>
      <div class="form-group" id='discordMsgOut' style='display: none;'>
        <p style="user-select: auto;"class="btn btn-success submit-btn btn-block" id='discordMessage'>...</p>
      </div>
      <div class="form-group">
        <label for="username">Name</label>
        <input type="text" class="form-control" name='name' id="name" placeholder="Enter show name" placeholder="<?php echo $row['name']?>" value="<?php echo $row['name'] ?>">
      </div>
      <div class="form-group">
        <label for="username">Host(s)</label>
        <input id="hosts" class="form-control autocUser" type="text" name="hosts" placeholder="Loading.."></input>
      </div>
      <div class="form-group">
        <label for="region">Day</label>
        <select name='day' class="form-control" id="day" value="<?php echo $timeDetails['day']?>">
          <option value="0" <?php if ($timeDetails['day'] == "0") echo "selected"?>>Monday</option>
          <option value="1" <?php if ($timeDetails['day'] == "1") echo "selected"?>>Tuesday</option>
          <option value="2" <?php if ($timeDetails['day'] == "2") echo "selected"?>>Wednesday</option>
          <option value="3" <?php if ($timeDetails['day'] == "3") echo "selected"?>>Thursday</option>
          <option value="4" <?php if ($timeDetails['day'] == "4") echo "selected"?>>Friday</option>
          <option value="5" <?php if ($timeDetails['day'] == "5") echo "selected"?>>Saturday</option>
          <option value="6" <?php if ($timeDetails['day'] == "6") echo "selected"?>>Sunday</option>
        </select>
      </div>
      <div class="form-group">
        <label for="region">Slot Start</label>
        <select name='time' class="form-control" id="time" value="<?php echo $timeDetails['timestart']?>">
          <option value="00" <?php if ($timeDetails['timestart'] == "00") echo "selected"?>>00:00</option>
          <option value="01" <?php if ($timeDetails['timestart'] == "01") echo "selected"?>>01:00</option>
          <option value="02" <?php if ($timeDetails['timestart'] == "02") echo "selected"?>>02:00</option>
          <option value="03" <?php if ($timeDetails['timestart'] == "03") echo "selected"?>>03:00</option>
          <option value="04" <?php if ($timeDetails['timestart'] == "04") echo "selected"?>>04:00</option>
          <option value="05" <?php if ($timeDetails['timestart'] == "05") echo "selected"?>>05:00</option>
          <option value="06" <?php if ($timeDetails['timestart'] == "06") echo "selected"?>>06:00</option>
          <option value="07" <?php if ($timeDetails['timestart'] == "07") echo "selected"?>>07:00</option>
          <option value="08" <?php if ($timeDetails['timestart'] == "08") echo "selected"?>>08:00</option>
          <option value="09" <?php if ($timeDetails['timestart'] == "09") echo "selected"?>>09:00</option>
          <option value="10" <?php if ($timeDetails['timestart'] == "10") echo "selected"?>>10:00</option>
          <option value="11" <?php if ($timeDetails['timestart'] == "11") echo "selected"?>>11:00</option>
          <option value="12" <?php if ($timeDetails['timestart'] == "12") echo "selected"?>>12:00</option>
          <option value="13" <?php if ($timeDetails['timestart'] == "13") echo "selected"?>>13:00</option>
          <option value="14" <?php if ($timeDetails['timestart'] == "14") echo "selected"?>>14:00</option>
          <option value="15" <?php if ($timeDetails['timestart'] == "15") echo "selected"?>>15:00</option>
          <option value="16" <?php if ($timeDetails['timestart'] == "16") echo "selected"?>>16:00</option>
          <option value="17" <?php if ($timeDetails['timestart'] == "17") echo "selected"?>>17:00</option>
          <option value="18" <?php if ($timeDetails['timestart'] == "18") echo "selected"?>>18:00</option>
          <option value="19" <?php if ($timeDetails['timestart'] == "19") echo "selected"?>>19:00</option>
          <option value="20" <?php if ($timeDetails['timestart'] == "20") echo "selected"?>>20:00</option>
          <option value="21" <?php if ($timeDetails['timestart'] == "21") echo "selected"?>>21:00</option>
          <option value="22" <?php if ($timeDetails['timestart'] == "22") echo "selected"?>>22:00</option>
          <option value="23" <?php if ($timeDetails['timestart'] == "23") echo "selected"?>>23:00</option>
        </select>
      </div>

      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Submit</button>
        <a href="Admin.Shows" class="web-page" id='new' style="display: none;"><button class="btn btn-success mr-2">Back to shows</button></a>
        <a href="Admin.Shows" class="web-page" id='cancel'><button class="btn btn-light">Cancel</button></a>
        <a href="#" id='delete'><button class="btn btn-danger">Delete Show</button></a>
      </div>

    </form>
  </div>
</div>
<script>
$.ajax({
    type: 'POST',
    url: `scripts/managePermShows.php?action=get&show=<?php echo $row['id']?>`
}).done(function(response) {
  var prePop = JSON.parse(response);
  $("#hosts").tokenInput("./scripts/managePermShows.php?action=findUser", {
    theme: "mac",
    preventDuplicates: true,
    hintText: "Search for a user to be a host",
    searchingText: '<i class="fas fa-circle-notch fa-spin" style="color: #fff; font-size: 15px; margin: auto; text-align: center; margin-left: 85px;"></i>',
    prePopulate: prePop,
    onAdd: function (item) {
        var assigned = item.id;
        var admin = $(this).attr("data-id");
        
    },
    onDelete: function (item) {
        var assigned = item.id;
        var admin = $(this).attr("data-id");
        
    },
  });
});
$(function() {

  var form = $('#editShow');
  $(form).submit(function(event) {
      var error = false;
      var errorMessage = '';
      event.preventDefault();
      console.log("Submitted");
      var formData = $(form).serialize();
      var hosts = $('#hosts');
      var name = $('#name');
      if (hosts.val() == "" || hosts.val() == null || name.val() == null || name.val() == null) {
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
          url: 'scripts/managePermShows.php?action=edit&show=<?php echo $row['id']?>',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'updated') {
          $('#errorFieldOut').fadeOut();
          newSuccess("Show updated! Slot will automatically book on timetable reset.");
          urlRoute.loadPage("Admin.Shows")
        } else {
          $('#errorFieldOut').fadeOut();
          newError("Unknown error occured.");
        }
      }).fail(function (response) {
        $('#errorFieldOut').fadeOut();
          newError("Unknown error occured.");
      });
    });
});

$(document).on("click","#delete", function() {
    urlRoute.loadPage("Manager.DeleteUser?id=<?php echo $id ?>")
  });
</script>
