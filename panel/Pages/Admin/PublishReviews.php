<?php
$perm = 4;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Publish Reviews";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
    <div class="card" style="width: 50%; margin: auto;">
      <div class="card-head">
        <h1>Are you <strong>SURE</strong>?</h1>
        <div class="card-actions">
        <a href="HDJ.Reviews" class="web-page">
          <button class="profile-close-button btn btn-light mr-2">Back</button>
        </a>
        <a id="publishReview">
          <button class="profile-close-button btn btn-light mr-2">Publish</button>
        </a>
        </div>
      </div>
      <div class="card-body">
        <h1 class="card-title">THIS WILL CLEAR ASSIGNMENTS!</h1>
        <h1 class="card-title">AND SEND REVIEWS OUT</h1>
      </div>
    </div>
<script>
$('#publishReview').on('click', function() {
  $.ajax({
      type: 'POST',
      url: 'scripts/reviews.php?action=publish'
  }).done(function(response) {
      if (response == 'updated') {
        newSuccess("The reviews have been published and assignments have been cleared!");
      }
  });
})

</script>
