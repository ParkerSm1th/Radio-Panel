<?php
$perm = 4;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Review Management";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
    <div class="row">
      <div class="col-md-4 col-sm-12">
        <div class="block">
          <h4>Publish Reviews</h4>
          <p>Publish reviews by Senior Staff+ & clear assignments</p>
          <button id="publish" class="profile-close-button btn btn-light mr-2">Publish</button>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="block">
          <h4>Assign Reviews</h4>
          <p>Opens the page for you to start assigning reviews</p>
          <button id="assign" class="profile-close-button btn btn-light mr-2">Open</button>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="block">
          <h4>Submitted Reviews</h4>
          <p>View all submitted reviews before publishing</p>
          <button id="view" class="profile-close-button btn btn-light mr-2">View</button>
        </div>
      </div>
    </div>
<script>
$('#publish').on('click', function() {
  urlRoute.loadPage("Admin.PublishReviews");
})
$('#assign').on('click', function() {
  urlRoute.loadPage("Admin.AssignReviews");
})

$('#view').on('click', function() {
  urlRoute.loadPage("Admin.SubmittedReviews");
})

</script>
