<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
include('../../includes/header.php');
include('../../includes/config.php');
if ($_GET['id'] == null) {
  ?>
    <script>urlRoute.loadPage("Staff.Dashboard")</script>
  <?php
  exit();
}
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row['username'] == null) {
  ?>
    <script>urlRoute.loadPage("Manager.Staff")</script>
  <?php
  exit();
}
if ($row['permRole'] == 1) {
  $color = 'dstaff-text';
}
if ($row['permRole'] == 2) {
  $color = 'sstaff-text';
}
if ($row['permRole'] == 3) {
  if ($_SESSION['loggedIn']['permRole'] >= 4) {
    $color = 'manager-text';
  } else {
    ?>
      <script>urlRoute.loadPage("Manager.Staff")</script>
    <?php
    exit();
  }
}
if ($row['permRole'] == 4) {
  if ($_SESSION['loggedIn']['permRole'] >= 5) {
    $color = 'admin-text';
  } else {
    ?>
      <script>urlRoute.loadPage("Manager.Staff")</script>
    <?php
    exit();
  }
}

if ($row['permRole'] == 5 || $row['permRole'] == 6) {
  ?>
    <script>urlRoute.loadPage("Manager.Staff")</script>
  <?php
  exit();
}
 ?>
 <div class="card">
   <div class="card-body">
     <h1 class="card-title">Delete User</h1>
     <form class="forms-sample" id='deleteUser' action="#">
       <div class="form-group" id='errorFieldOut' style='display: none;'>
         <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
       </div>
       <div class="form-group" id='discordMsgOut' style='display: none;'>
         <p style="user-select: auto;"class="btn btn-success submit-btn btn-block" id='discordMessage'>...</p>
       </div>
     <img style="height: 55px;border-radius: 100%;margin-bottom: 10px;" src="<?php echo $row['avatarURL']?>" onerror="this.src='https://itspower.net/v2/_assets/logo.png'" alt="image">
     <p class="card-description">Are you sure you want to delete <span class="<?php echo $color ?> userLink" onclick="loadProfile(<?php echo $id ?>)" style="font-size: 15px;"><?php echo $row['username'] ?></span>?</p>
     <div class="form-group">
       <button class="btn btn-success mr-2" id='submit'>Yes</button>
       <a href="Manager.Staff" class="web-page" id='cancel'><button class="btn btn-light">No, Cancel</button></a>
       <a href="Manager.Staff" class="web-page" id='backBut' style='display: none'><button class="btn btn-light">Back to staff..</button></a>
     </form>
     </div>
   </div>
 </div>
 <script>
 $(function() {

   var form = $('#deleteUser');
   $(form).submit(function(event) {
       var error = false;
       var errorMessage = '';
       event.preventDefault();
       console.log("Submitted");
       if (error) {
         $('#errorFieldOut').fadeIn();
         $('#errorField').html(errorMessage);
         return true;
       }

       $.ajax({
           type: 'POST',
           url: './scripts/deleteUser.php',
           data: {id: <?php echo $id ?>}
       }).done(function(response) {
         console.log(response);
         if (response == 'deleted') {
           $('#errorFieldOut').fadeIn();
           $('#errorField').removeClass('btn-danger');
           $('#errorField').addClass('btn-success');
           $('#errorField').html('Success! User deleted.');
           $('#submit').hide();
           $('#cancel').hide();
           $('#backBut').show();
         } else {
           $('#errorField').addClass('btn-danger');
           $('#errorField').removeClass('btn-success');
           $('#errorFieldOut').fadeIn();
           $('#errorField').html('Unknown error occured..');
         }
       }).fail(function (response) {
           $('#errorFieldOut').fadeIn();
           $('#errorField').html('Unknown error occured.');
       });
     });
 });
 </script>
