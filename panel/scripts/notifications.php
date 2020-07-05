<?php
 include('../includes/config.php');
 session_start();

 $stmt = $conn->prepare("SELECT * FROM sessions WHERE session = :id");
 $stmt->bindParam(':id', session_id());
 $stmt->execute();
 $row = $stmt->fetch(PDO::FETCH_ASSOC);
 $count = $stmt->rowCount();
 if ($count == 0) {
   session_destroy();
   ?>
    <script>login();</script>
   <?php
   exit();
 } else {
   if ($row['page'] != $_COOKIE['currentPage']) {
     $stmt = $conn->prepare("UPDATE sessions SET page = :page WHERE session = :id");
     $stmt->bindParam(':id', session_id());
     $stmt->bindParam(':page', $_COOKIE['currentPage']);
     $stmt->execute();
   }
   if ($row['refresh'] == "1") {
    ?>
      <script>refreshNav();</script>
    <?php
    $stmt = $conn->prepare("UPDATE sessions SET refresh = 0 WHERE user = :id");
    $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
    $stmt->execute();
   }
 }

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
 $extra = false;
 date_default_timezone_set('Europe/London');
 $date = date( 'N' ) - 1;
 $id = $_SESSION['loggedIn']['id'];
 $hour = date( 'H' );
 $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND booked = :id AND timestart = :hour");
 $stmt->bindParam(':id', $id);
 $stmt->bindParam(':day', $date);
 $stmt->bindParam(':hour', $hour);
 $stmt->execute();
 $count = $stmt->rowCount();

 date_default_timezone_set('Europe/London');
 $date = date( 'N' ) - 1;
 $id = $_SESSION['loggedIn']['id'];
 $hour = date( 'H' ) + 1;
 $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND booked = :id AND timestart = :hour");
 $stmt->bindParam(':id', $id);
 $stmt->bindParam(':day', $date);
 $stmt->bindParam(':hour', $hour);
 $stmt->execute();
 $count2 = $stmt->rowCount();

 $url = "https://api.keyfm.net/stats";
 $ch = curl_init();
 curl_setopt($ch,CURLOPT_URL, $url);
 curl_setopt($ch,CURLOPT_GET, true);
 curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
 $result = curl_exec($ch);
 curl_close($ch);
 $stats = json_decode($result, true);
 if ($result == false) {
   echo 'error';
 }
 if ($stats['success'] == true) {
   $currentDJ = $stats['currentDJ']['id'];
 }
 $stat = $conn->prepare("SELECT * FROM song_log ORDER BY id DESC LIMIT 30");
 $stat->execute();
 $current = $stat->fetch(PDO::FETCH_ASSOC);
 if ($count == 0 && $currentDJ != $_SESSION['loggedIn']['id'] && $_SESSION['loggedIn']['permRole'] < 4) {
   ?>
   <script>
    if (urlRoute.currentCode == "/Radio.Streamer") {
      urlRoute.loadPage("Staff.Dashboard");
      newError("You are not the current DJ!");
    }
   </script>
   <?php
 }
 if ($count == 1 || $currentDJ == $_SESSION['loggedIn']['id']) {
   $extra = true;
   ?>
   <div>
   <a class="dropdown-item preview-item">
     <div class="preview-thumbnail">
       <div class="preview-icon bg-success ?>">
         <i class="far fa-check"></i>
       </div>
     </div>
     <div class="preview-item-content">
       <h6 class="preview-subject font-weight-medium text-dark">Streamer Panel</h6>
       <p class="font-weight-light small-text" style="font-size: 0.75rem; word-break: break-all; width: 250px; line-height: 1.2.">
         You now have access to the Streamer Panel.
       </p>
       <p style="cursor: pointer;" class="streamerPanel font-weight-light small-text">
         Click here to access the streamer panel.
       </p>
     </div>
   </a>
   <div class="dropdown-divider"></div>
   </div>
   <?php
 }

 if ($currentDJ != $_SESSION['loggedIn']['id'] && $count == 1) {
   $extra = true;
   ?>
   <div>
   <a class="dropdown-item preview-item">
     <div class="preview-thumbnail">
       <div class="preview-icon bg-danger ?>">
         <i class="far fa-clock"></i>
       </div>
     </div>
     <div class="preview-item-content">
       <h6 class="preview-subject font-weight-medium text-dark">You are missing your booked slot!</h6>
       <p class="font-weight-light small-text" style="font-size: 0.75rem; word-break: break-all; width: 250px; line-height: 1.2.">
         Missing a slot will result in a warning!
       </p>
     </div>
   </a>
   <div class="dropdown-divider"></div>
   </div>
   <?php
 } else if ($currentDJ == $_SESSION['loggedIn']['id'] && $count == 0) {
   $extra = true;
   ?>
   <div>
   <a class="dropdown-item preview-item">
     <div class="preview-thumbnail">
       <div class="preview-icon bg-success ?>">
         <i class="far fa-life-ring"></i>
       </div>
     </div>
     <div class="preview-item-content">
       <h6 class="preview-subject font-weight-medium text-dark">Covering this slot?</h6>
       <p class="font-weight-light small-text" style="font-size: 0.75rem; word-break: break-all; width: 250px; line-height: 1.2.">
         You can cover this slot by clicking below.
       </p>
       <p style="cursor: pointer;" class="coverSlot font-weight-light small-text" data-nid="<?php echo $row['id'] ?>">
         Click here to cover the slot.
       </p>
     </div>
   </a>
   <div class="dropdown-divider"></div>
   </div>
   <?php
 }
 if ($count2 == 1) {
   $extra = true;
   ?>
   <div>
   <a class="dropdown-item preview-item">
     <div class="preview-thumbnail">
       <div class="preview-icon bg-warning ?>">
         <i class="far fa-clock"></i>
       </div>
     </div>
     <div class="preview-item-content">
       <h6 class="preview-subject font-weight-medium text-dark">You have a slot soon!</h6>
       <p class="font-weight-light small-text" style="font-size: 0.75rem; word-break: break-all; width: 250px; line-height: 1.2.">
         You currently have the next slot booked!
       </p>
     </div>
   </a>
   <div class="dropdown-divider"></div>
   </div>
   <?php
 }
 if ($extra == false) {
   $stmt = $conn->prepare("SELECT * FROM notifications WHERE userID = :id AND active = '1' ORDER BY id");
   $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
   $stmt->execute();
   $count = $stmt->rowCount();
   if ($count == 0) {
     ?>
     <a class="dropdown-item" style="width: 250px; text-align: center; padding-bottom: 15px;" >
       <p class="mb-0 font-weight-normal float-left" id="notCount">You have <?php echo $count ?> new notifications
       </p>
     </a>
     <?php
   }
 }
 /*

 */
  ?>
  <script>
  $('.streamerPanel').click(function(ev) {
    ev.preventDefault();
    ev.stopPropagation();
    urlRoute.loadPage("Radio.Streamer");
  });
  $('.coverSlot').click(function(ev) {
    ev.preventDefault();
    ev.stopPropagation();
    $.ajax({
        type: 'POST',
        url: './scripts/coverCurrent.php'
    }).done(function(response) {
      console.log(response);
      if (response == 'covered') {
        var object = $(this);
        object.parent().parent().parent().fadeOut();
        var math = globalcount - 1;
        globalcount = math;
        $("#notCount").html("You have " + math + " new notifications");
        object.parent().parent().css('display', "none !important")
        object.closest("div.dropdown-divider").fadeOut();
        notifications();
        urlRoute.loadPage('Radio.Timetable');
      } else {
        console.log('error');
      }
    }).fail(function (response) {
       console.log('error');
    });
  });
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
