<?php
include('../includes/config.php');
session_start();
$stmt = $conn->prepare("SELECT * FROM notifications WHERE userID = :id AND active = '1' ORDER BY id");
$stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
$stmt->execute();
$count = $stmt->rowCount();
?>
<div class="dropdown-divider"></div>
<a class="dropdown-item" style="width: 250px; text-align: center;" >
  <p class="mb-0 font-weight-normal float-left" id="notCount">You have <?php echo $count ?> new notifications
  </p>
</a>
<div class="dropdown-divider"></div>
<?php

$stmt = $conn->prepare("SELECT * FROM notifications WHERE userID = :id AND active = '1' ORDER BY id");
$stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
$stmt->execute();

foreach($stmt as $row) {
  $id = $row['id'];
  ?>
  <div>
  <a class="dropdown-item preview-item">
    <div class="preview-thumbnail">
      <div class="preview-icon bg-<?php echo $row['type'] ?>">
        <i class="<?php echo $row['icon'] ?>"></i>
      </div>
    </div>
    <div class="preview-item-content">
      <h6 class="preview-subject font-weight-medium text-dark"><?php echo $row['header'] ?></h6>
      <p class="font-weight-light small-text" style="font-size: 0.75rem; word-break: break-all; width: 250px; line-height: 1.2.">
        <?php echo $row['content'] ?>
      </p>
      <p style="cursor: pointer;" class="notificationDismiss font-weight-light small-text" data-nid="<?php echo $row['id'] ?>">
        Click here to dismiss.
      </p>
    </div>
  </a>
  <div class="dropdown-divider"></div>
</div>
  <?php
}

/*

<a class="dropdown-item">
  <p class="mb-0 font-weight-normal float-left">You have 4 new notifications
  </p>
</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item preview-item">
  <div class="preview-thumbnail">
    <div class="preview-icon bg-warning">
      <i class="far fa-clock"></i>
    </div>
  </div>
  <div class="preview-item-content">
    <h6 class="preview-subject font-weight-medium text-dark">Slots</h6>
    <p class="font-weight-light small-text">
      You must book 2 more slots this week!
    </p>
    <p class="font-weight-light small-text">
      Click me to dismiss.
    </p>
  </div>
</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item preview-item">
  <div class="preview-thumbnail">
    <div class="preview-icon bg-info">
      <i class="fas fa-exclamation-circle"></i>
    </div>
  </div>
  <div class="preview-item-content">
    <h6 class="preview-subject font-weight-medium text-dark">Connection Information</h6>
    <p class="font-weight-light small-text">
      The connection information has changed.
    </p>
    <p class="font-weight-light small-text">
      Click me to dismiss.
    </p>
  </div>
</a>

*/
 ?>
 <script>
 var globalcount = <?php echo $count ?>;
 $('.notificationDismiss').click(function(ev) {
     ev.preventDefault();
     ev.stopPropagation();
     var object = $(this);
     var thing = this;
     $.ajax({
         type: 'POST',
         url: './scripts/deleteNotification.php',
         data: {id: thing.dataset.nid}
     }).done(function(response) {
       console.log(response);
       if (response == 'dl') {
         object.parent().parent().parent().fadeOut();
         var math = globalcount - 1;
         globalcount = math;
         $("#notCount").html("You have " + math + " new notifications");
         object.parent().parent().css('display', "none !important")
         object.closest("div.dropdown-divider").fadeOut();
         notifications();
       } else {
         console.log('error');
       }
     }).fail(function (response) {
        console.log('error');
     });
 });

 </script>
