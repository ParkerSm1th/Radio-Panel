<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 1;
$title = "New Page";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
<div class="card">
  <div class="card-head">
    <h1>New Page</h1>
  </div>
  <div class="card-body">
    <form class="forms-sample" id='newPage' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='errorField'></span>
      </div>
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" name='name' id="name" placeholder="Enter page name">
      </div>

      <div class="form-group">
        <label for="url">URL</label>
        <input type="text" class="form-control" name='url' id="url" placeholder="Enter URL">
      </div>

      <div class="form-group">
        <label for="rank">Nav Rank</label>
        <select name='rank' class="form-control" id="rank">
        <?php
        $stmt = $conn->prepare("SELECT * FROM nav_ranks ORDER BY id");
        $stmt->execute();

        foreach($stmt as $row) {
          $id = $row['id'];
          ?>
            <option value="<?php echo $id?>"><?php echo $row['name']?></option>
          <?php
        }?>
      </select>
      </div>

      <div class="form-group">
      <p class="card-description">Is this page in development?</p>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" name='dev' id="dev" class="form-check-input" checked=""> Development
          <i class="input-helper"></i></label>
        </div>
      </div>
      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Create</button>
        <a href="Dev.NewPage" class="web-page" id='new' style="display: none;"><button class="btn btn-success mr-2">Add Another</button></a>
        <a href="Dev.Pages" class="web-page"><button class="btn btn-light">Cancel</button></a>
      </div>

    </form>
  </div>
</div>
<script>
$(function() {

  var form = $('#newPage');
  $(form).submit(function(event) {
      var error = false;
      var errorMessage = '';
      event.preventDefault();
      console.log("Submitted");
      var formData = $(form).serialize();
      var name = $('#name');
      var url = $('#url');
      if (name.val() == null || name.val() == "" || url.val() == null || url.val() == "") {
        error = true;
        errorMessage = 'Please fill in all fields';
      }

      if (error) {
        $('#errorFieldOut').fadeIn();
        $('#errorField').html(errorMessage);
        return true;
      }

      $.ajax({
          type: 'POST',
          url: 'scripts/newPage.php',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'added') {
          newSuccess("New page has been added!");
          urlRoute.loadPage("Dev.Pages")
          refreshNav();
        } else {
          newError("Unknown error occured");
        }
      }).fail(function (response) {
        newError("Unknown error occured");
      });
    });
});
</script>
