<?php
$perm = 4;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Banned Songs";
include('../../includes/header.php');
include('../../includes/config.php');
ini_set('')
 ?>
<div class="card">
    <div class="card-head">
      <h1>Banned Songs</h1>
    <div class="card-actions">
      <a href="Admin.BanSong" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">Ban a Song</button>
      </a>
    </div>
    </div>
    <div class="table-responsive">
      <table class="table usersTable">
        <thead>
          <tr>
            <th>
              Song
            </th>
            <th>
              Banned By
            </th>
            <th>
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            $stmt = $conn->prepare("SELECT * FROM banned WHERE type = 0");
            $stmt->execute();

            foreach($stmt as $row) {
              $user = $conn->prepare("SELECT * FROM users WHERE id = :id");
              $user->bindParam(":id", $row['banned_by']);
              $user->execute();
              $data = $user->fetch(PDO::FETCH_ASSOC);
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $row['artist']; ?> - <?php echo $row['song'];?>
                </td>
                <td>
                  <?php echo getUserSpan($data['id']) ?>
                </td>
                <td>
                  <div class="tableButton deleteBan" data-id="<?php echo $row['id']?>">
                    <span class="cTooltip"><i  class="fas fa-trash"></i><b title="Delete Ban"></b></span>
                  </div>
                </td>
              </tr>
              <?php
            }

            ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card">
    <div class="card-head">
      <h1>Banned Artists</h1>
    <div class="card-actions">
      <a href="Admin.BanSong?type=artist" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">Ban an Artist</button>
      </a>
    </div>
    </div>
    <div class="table-responsive">
      <table class="table usersTable">
        <thead>
          <tr>
            <th>
              Artist
            </th>
            <th>
              Banned By
            </th>
            <th>
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            $stmt = $conn->prepare("SELECT * FROM banned WHERE type = 1");
            $stmt->execute();

            foreach($stmt as $row) {
              $user = $conn->prepare("SELECT * FROM users WHERE id = :id");
              $user->bindParam(":id", $row['banned_by']);
              $user->execute();
              $data = $user->fetch(PDO::FETCH_ASSOC);
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $row['artist'];?>
                </td>
                <td>
                  <?php echo getUserSpan($data['id']) ?>
                </td>
                <td>
                  <div class="tableButton deleteBan" data-id="<?php echo $row['id']?>">
                    <span class="cTooltip"><i class="fas fa-trash"></i><b title="Delete Ban"></b></span>
                  </div>
                </td>
              </tr>
              <?php
            }

            ?>
        </tbody>
      </table>
    </div>
  </div>


<script>
$(".deleteBan").on("click", function () {
  var elem = this;
  $(this).children('span').children('i').removeClass('fa-trash');
  $(this).children('span').children('i').addClass('fa-circle-notch');
  $(this).children('span').children('i').addClass('fa-spin');
  $.ajax({
      type: 'POST',
      url: 'scripts/banSong.php?action=remove&id=' + $(this).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "removed") {
      $(elem).parent().parent().fadeOut();
      newSuccess("You have deleted that banned song or artist!");
    } else {
      newError("An unknown error occured.");
    }
  });
});
</script>