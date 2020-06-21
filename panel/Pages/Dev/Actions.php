<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 1;
$title = "Dev Actions";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
    <div class="row">
      <div class="col-md-4 col-sm-12">
        <div class="block">
          <h4>Clear Timetable</h4>
          <p>Use this to clear the timetable</p>
          <button id="timetable" class="profile-close-button btn btn-light mr-2">Clear</button>
        </div>
      </div>
    </div>
<script>
$('#timetable').on('click', function() {
  $.ajax({
      type: 'POST',
      url: 'scripts/clearTimetable.php'
  }).done(function(response) {
      if (response == 'updated') {
        newSuccess("The timetable has been cleared");
      }
  });
})

</script>
