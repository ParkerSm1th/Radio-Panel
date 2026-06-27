<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "User Actions";
require APP_INCLUDES . '/page_header.php';

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

if ($row['permRole'] == 5) {
  if ($_SESSION['loggedIn']['permRole'] >= 6) {
    $color = 'executive-text';
  } else {
    ?>
      <script>urlRoute.loadPage("Manager.Staff")</script>
    <?php
    exit();
  }
}

if ($row['permRole'] == 6 && $_SESSION['loggedIn']['id'] != '1') {
  ?>
    <script>urlRoute.loadPage("Manager.Staff")</script>
  <?php
  exit();
} else {
  $color = 'owner-text';
}
 ?>
    <div class="row">
      <div class="col-md-4 col-sm-12">
        <div class="block">
          <h4>Log Out <span class="<?php echo $color ?> userLink" onclick="loadProfile(<?php echo $id ?>)"><?php echo $row['username'] ?></span></h4>
          <p>Logs the user out</p>
          <button id="logout" class="profile-close-button btn btn-light mr-2">Logout</button>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="block">
          <h4>Reset <span class="<?php echo $color ?> userLink" onclick="loadProfile(<?php echo $id ?>)"><?php echo $row['username'] ?>'s Discord</span></h4>
          <p>Resets the users discord forcing them to relink it.</p>
          <button id="resetDiscord" class="profile-close-button btn btn-light mr-2">Reset</button>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="block">
          <h4>Suspend <span class="<?php echo $color ?> userLink" onclick="loadProfile(<?php echo $id ?>)"><?php echo $row['username'] ?></span></h4>
          <p>Suspends the user</p>
          <button id="suspendUser" class="profile-close-button btn btn-light mr-2">Suspend</button>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="block">
          <h4>View <span class="<?php echo $color ?> userLink" onclick="loadProfile(<?php echo $id ?>)"><?php echo $row['username'] ?></span>'s Review History</h4>
          <p>Shows you this users review history</p>
          <button id="rHistory" class="profile-close-button btn btn-light mr-2">View</button>
        </div>
      </div>
    </div>
<script>
$('#rHistory').on('click', function() {
  urlRoute.loadPage("Manager.ReviewHistory?id=<?php echo $row['id'] ?>");
})
$('#logout').on('click', function() {
  $.ajax({
      type: 'POST',
      url: window.RadioPanelApp.apiBase + '/userActions?action=logout&id=<?php echo $row['id'] ?>'
  }).done(function(response) {
      if (response == 'updated') {
        newSuccess("They have been logged out.");
      }
  });
})
$('#suspendUser').on('click', function() {
  $.ajax({
      type: 'POST',
      url: window.RadioPanelApp.apiBase + '/userActions?action=suspend&id=<?php echo $row['id'] ?>'
  }).done(function(response) {
      if (response == 'updated') {
        newSuccess("They have been suspended and they have been logged out.");
      }
  });
})
$('#resetDiscord').on('click', function() {
  $.ajax({
      type: 'POST',
      url: window.RadioPanelApp.apiBase + '/resetDiscord?id=<?php echo $row['id'] ?>'
  }).done(function(response) {
      if (response == 'updated') {
        newSuccess("Their discord has been updated and they have been logged out.");
      }
  });
})

</script>
