<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "New Perm Show";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
 <style>
   ul.token-input-list-mac {
     margin: 0;
   }
 </style>
<div class="card">
  <div class="card-body">
    <h4 class="card-title"><?php echo $row['name']?></h4>
    <form class="forms-sample" id='newShow' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
      </div>
      <div class="form-group" id='discordMsgOut' style='display: none;'>
        <p style="user-select: auto;"class="btn btn-success submit-btn btn-block" id='discordMessage'>...</p>
      </div>
      <div class="form-group">
        <label for="username">Name</label>
        <input type="text" class="form-control" name='name' id="name" placeholder="Enter show name" placeholder="Enter show name">
      </div>
      <div class="form-group">
        <label for="username">Host(s)</label>
        <input id="hosts" class="form-control autocUser" type="text" name="hosts" placeholder="Loading.."></input>
      </div>
      <div class="form-group">
        <label for="region">Day</label>
        <select name='day' class="form-control" id="day">
          <option value="0" selected>Monday</option>
          <option value="1">Tuesday</option>
          <option value="2">Wednesday</option>
          <option value="3">Thursday</option>
          <option value="4">Friday</option>
          <option value="5">Saturday</option>
          <option value="6">Sunday</option>
        </select>
      </div>
      <div class="form-group">
        <label for="region">Slot Start</label>
        <select name='time' class="form-control" id="time">
          <option value="00" selected>00:00</option>
          <option value="01">01:00</option>
          <option value="02">02:00</option>
          <option value="03">03:00</option>
          <option value="04">04:00</option>
          <option value="05">05:00</option>
          <option value="06">06:00</option>
          <option value="07">07:00</option>
          <option value="08">08:00</option>
          <option value="09">09:00</option>
          <option value="10">10:00</option>
          <option value="11">11:00</option>
          <option value="12">12:00</option>
          <option value="13">13:00</option>
          <option value="14">14:00</option>
          <option value="15">15:00</option>
          <option value="16">16:00</option>
          <option value="17">17:00</option>
          <option value="18">18:00</option>
          <option value="19">19:00</option>
          <option value="20">20:00</option>
          <option value="21">21:00</option>
          <option value="22">22:00</option>
          <option value="23">23:00</option>
        </select>
      </div>

      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Create</button>
        <a href="Admin.Shows" class="web-page" id='cancel'><button class="btn btn-light">Cancel</button></a>
      </div>

    </form>
  </div>
</div>
<script>
$("#hosts").tokenInput("./scripts/managePermShows.php?action=findUser", {
    theme: "mac",
    preventDuplicates: true,
    hintText: "Search for a user to be a host",
    searchingText: '<i class="fas fa-circle-notch fa-spin" style="color: #fff; font-size: 15px; margin: auto; text-align: center; margin-left: 85px;"></i>',
    onAdd: function (item) {
        var assigned = item.id;
        var admin = $(this).attr("data-id");
        
    },
    onDelete: function (item) {
        var assigned = item.id;
        var admin = $(this).attr("data-id");
        
    },
  });
$(function() {

  var form = $('#newShow');
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
          url: 'scripts/managePermShows.php?action=new',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'created') {
          $('#errorFieldOut').fadeOut();
          newSuccess("Show created! Slot will automatically book on timetable reset.");
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
