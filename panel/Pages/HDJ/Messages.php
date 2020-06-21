<?php
$perm = 2;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Panel Made Messages";
include('../../includes/header.php');
include('../../includes/config.php');
  $stmt = $conn->prepare("UPDATE users SET viewed_info = 1 WHERE id = :id");
  $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
  $stmt->execute();

  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  $action = "Viewed connection info";
  $log = $conn->prepare("INSERT INTO panel_log (name, ip, times, action) VALUES (:name, :ip, CURRENT_TIMESTAMP, :action)");
  $log->bindParam(':name', $_SESSION['loggedIn']['id']);
  $log->bindParam(':ip', $ip);
  $log->bindParam(':action', $action);
  $log->execute();
 ?>

<style>
input:hover {
 cursor: pointer;
}
.form-group h1 {
  color: #fff;
  text-align: center;
  font-size: 16px;
}
p.form-control {
  line-height: 17px !important;
  cursor: pointer;
}
 </style>
<div class="card">
  <div class="card-body">
    <div class="alert alert-success text-center" role="alert">
       These are pre-made messages for you to copy/paste in discord to remind staff about things.
     </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <h1>DJ Minimums Reminder</h1>
          <p id="disc" class="form-control">**DJ Minimums Reminder**<br>
            As a Radio DJ, you must complete your 3 slot minimum, this is imperative to keeping your position without warning and passing your trial. The following DJs have yet to complete **or** book their minimums this week, the number next to your name is how many slots you have left to book & complete:<br><br>

            <?php
              $stmt = $conn->prepare("SELECT * FROM users WHERE radio = '1' AND developer = '0' AND permRole < 6 ORDER BY username");
              $stmt->execute();

              foreach($stmt as $row) {
                $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id");
                $stmt->bindParam(":id", $row['id']);
                $stmt->execute();
                $count = $stmt->rowCount();
                $awayS = $conn->prepare("SELECT * FROM post_away WHERE user = :id AND (status = 1 OR status = 0)");
                $awayS->bindParam(':id', $row['id']);
                $awayS->execute();
                $away = $awayS->rowCount();
                if ($away == 0) {
                  if ($count < 3) {
                    if ($row['discord'] == null) {
                      $discord = "**" . $row['username'] . "**";
                    } else {
                      $discord = "<@" . $row['discord_id'] . ">";
                    }
                    $difference = 3 - $count;
                    if ($difference == 3) {
                      ?>
              :three: <?php echo $discord ?><br>
                      <?php
                    } else if ($difference == 2) {
                      ?>
              :two: <?php echo $discord ?><br>
                      <?php
                    } else if ($difference == 1) {
                      ?>
              :one: <?php echo $discord ?><br>
                      <?php
                    }
                  }
                }
              }
            ?>
            <br>
            *If you cannot complete your minimum this week, please contact a Head DJ*</p>
          </p>
        </div>
      </div>
      <div class="col-md-6">
      <div class="form-group">
          <h1>Discord Linking</h1>
          <p id="min" class="form-control">**Discord Link Reminder**<br>
            All staff must link their discords to the panel, the following DJs have yet to link their discords and therefore will be dismissed from the team in 24hrs if their discords have not been linked:<br><br>

            <?php
              $stmt = $conn->prepare("SELECT * FROM users WHERE discord = '' ORDER BY id");
              $stmt->execute();

              foreach($stmt as $row) {
                ?>
                  **<?php echo $row['username']?>**<br>
                <?php
              }
            ?>
            <br>
            *To link your discord simply login to the panel https://staff.keyfm.net*</p>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function SelectText(element) {
    var doc = document;
    var text = doc.getElementById(element);    
    if (doc.body.createTextRange) { // ms
        var range = doc.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if (window.getSelection) {
        var selection = window.getSelection();
        var range = doc.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);

    }
}

$(function() {
    $('p').click(function() {
        SelectText($(this).attr("id"));

    });
});
</script>
