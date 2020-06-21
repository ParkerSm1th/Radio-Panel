<?php
$perm = 2;
$media = 0;
$radio = 1;
$dev = 0;
$title = "All Requests";
include('../../includes/header.php');
include('../../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}
 ?>
<div class="card" id="users">
  <div class="card-body" >
    <div class="card-actions">
      <a href="Radio.Requests" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">Clear Filters</button>
      </a>
      <a href="Radio.Requests?filter=me" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">My Requests</button>
      </a>
    </div>
    <div class="table-responsive" style="overflow: visible;">
      <table class="table usersTable">
        <tbody id="requestTable">
          <?php
            if ($_GET['filter'] == "me") {
              $stmt = $conn->prepare("SELECT * FROM requests WHERE dj = :dj ORDER BY id DESC");
              $stmt->bindParam(':dj', $_SESSION['loggedIn']['id']);
              $stmt->execute();
              $count = $stmt->rowCount();
            } else {
              $stmt = $conn->prepare("SELECT * FROM requests ORDER BY id DESC");
              $stmt->execute();
              $count = $stmt->rowCount();
            }
            if ($count > 0) {
            ?>
            <h2 style="margin: auto;
color: #ffffffe8;
padding-top: 30px;
text-align: center;
display: none;" id="noRequests">No new requests 😊</h2>
            <?php
            foreach($stmt as $row) {
                $dj = $row['dj'];
                if ($dj == 0) {
                  $toDJ = "Auto DJ";
                } else {
                  $user = $conn->prepare("SELECT * FROM users WHERE id = :id");
                  $user->bindParam(':id', $dj);
                  $user->execute();
                  $data = $user->fetch(PDO::FETCH_ASSOC);
                  $toDJ = $data['username'];
                  $toDJImg = $data['avatarURL'];
                }
                ?>
                  <tr>
                    <td class="py-1">
                      <?php if ($dj == 0) {
                        ?>
                          <span><?php echo $toDJ ?></span>
                        <?php
                      }  else {
                        ?>
                          <img src="../profilePictures/<?php echo $toDJImg?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $data['id'] ?>)"><?php echo $toDJ ?></span>
                        <?php
                      }?>
                    </td>
                    <td>
                      <?php if ($row['type'] == 0) {
                        ?>
                          <span class="cTooltip"><i class="fas fa-user-music"></i><b title="Request"></b></span> <strong><?php echo $row['name']?></strong> <?php echo $row['artist'] ?> - <?php echo $row['song'] ?> <?php
                          if ($row['message'] !== "") {
                            ?> &bull;
                            <?php echo $row['message'];
                          }
                      } else {
                        ?>
                          <span class="cTooltip"><i class="fas fa-user-tag"></i><b title="Shoutout"></b></span> <strong><?php echo $row['name']?></strong> <?php echo $row['message']; ?>
                        <?php
                      }?>
                    </td>
                    <td>
                      <?php if ($admin) {?><span class="cTooltip"><i class='fas fa-siren-on'></i><b title="<?php echo $row['ip']?>"></b></span> <?php } echo $row['times'] ?>
                    </td>
                    <td>
                      <?php
                      if ($admin || $row['dj'] == $_SESSION['loggedIn']['id']) {
                        ?>
                        <div class="tableButton">
                          <span class="cTooltip"><i id="reportRequest" data-id="<?php echo $row['id']?>" class="fas fa-flag"></i><b title="Report Request"></b></span>
                        </div>
                        <div class="tableButton deleteRequest" data-id="<?php echo $row['id']?>">
                          <span class="cTooltip"><i id="deleteRequest" class="fas fa-trash"></i><b title="Delete Request"></b></span>
                        </div>
                        <?php
                      } else {
                        ?>
                        <div class="tableButton">
                          <span class="cTooltip"><i id="reportRequest" data-id="<?php echo $row['id']?>" class="fas fa-flag"></i><b title="Report Request"></b></span>
                        </div>
                        <?php
                      }
                       ?>
                    </td>
                  </tr>
                  <?php
                }
              } else {
                ?>
                <h2 style="margin: auto;
    color: #ffffffe8;
    padding-top: 30px;
    text-align: center;" id="noRequests">No new requests 😊</h2>
                <?php
              }

            ?>
        </tbody>
      </table>
    </div>


  </div>
</div>
<script>
var latest = null;
clearInterval(pageInt);
pageInt = null;
var pageInt = setInterval(checkNew, 5000)
function checkNew() {
  $.ajax({
      type: 'GET',
      url: 'scripts/newestRequest.php?streamer=0'
  }).done(function(response) {
    if (latest == null) {
      latest = response;
      return true;
    }
    if (latest !== response) {
      latest = response;
      $("#noRequests").fadeOut();
      $("#requestTable").prepend(latest);
    }
  });
}
function deleteRequest(elem) {
  $(elem).parent().parent().fadeOut();
  $.ajax({
      type: 'POST',
      url: 'scripts/deleteRequest.php?id=' + $(elem).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "deleted") {
      newSuccess("You have deleted that request!");
    } else {
      newError("An unknown error occured.");
    }
  });
}
$(".deleteRequest").on("click", function () {
  $(this).parent().parent().fadeOut();
  $.ajax({
      type: 'POST',
      url: 'scripts/deleteRequest.php?id=' + $(this).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "deleted") {
      $(this).parent().parent().fadeOut();
      newSuccess("You have deleted that request!");
    } else {
      newError("An unknown error occured.");
    }
  });
});
</script>
