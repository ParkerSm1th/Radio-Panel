<?php
$perm = 2;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Posted Away Users";
include('../../includes/header.php');
include('../../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}
 ?>
    <div class="table-responsive">
      <table class="table usersTable">
        <thead>
          <tr>
            <th>
              User
            </th>
            <th>
              Returns
            </th>
            <th>
              Reason
            </th>
            <th>
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
              $stmt = $conn->prepare("SELECT * FROM post_away WHERE status = 1 ORDER BY id DESC");
              $stmt->execute();

            foreach($stmt as $row) {
              $userID = $row['user'];
              $user = $conn->prepare("SELECT * FROM users WHERE id = :id");
              $user->bindParam(':id', $userID);
              $user->execute();
              $data = $user->fetch(PDO::FETCH_ASSOC);
              $now = date('d-m-Y', time());
              $timestampSeconds = $row['length'] / 1000;
              $done = date("d-m-Y", $timestampSeconds);
              $diff = strtotime($now) - strtotime($done); 
              $dateDiff = abs(round($diff / 86400)); 
              if ($dateDiff == 1) {
                $day = "";
              } else {
                $day = "s";
              }
              ?>
                <tr>
                  <td class="py-1">
                    <img src="../profilePictures/<?php echo $data['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $data['id'] ?>)"><?php echo $data['username'] ?></span>
                  </td>
                  <td>
                    <?php echo date("d-m-Y", $timestampSeconds); ?> (<strong><?php echo $dateDiff ?></strong> day<?php echo $day ?>)
                  </td>
                  <td>
                    <?php echo $row['reason'] ?>
                  </td>
                  <td>
                    <button data-id="<?php echo $row['user']?>" class="bringBack profile-close-button btn btn-danger mr-2">Bring Back Early</button>
                  </td>
                </tr>
                <?php
              }

            ?>
        </tbody>
      </table>
    </div>
<script>
$(".bringBack").on("click", function () {
  var elem = $(this);
  $.ajax({
      type: 'GET',
      url: 'scripts/postAway.php?returnOther=1&user=' + elem.attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == 'returned') {
      elem.parent().parent().fadeOut();
      newSuccess("They have been returned early..");
    } else {
      newError("An unknown error occured");
    }
  }).fail(function (response) {
    newError("An unknown error occured");
  });
});
</script>