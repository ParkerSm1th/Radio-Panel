<?php
$perm = 4;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Active Users";
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
              Page
            </th>
            <th>
              Times
            </th>
            <th>
              Logout
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
              $stmt = $conn->prepare("SELECT * FROM sessions ORDER BY id DESC");
              $stmt->execute();

            foreach($stmt as $row) {
              $userID = $row['user'];
              $user = $conn->prepare("SELECT * FROM users WHERE id = :id");
              $user->bindParam(':id', $userID);
              $user->execute();
              $data = $user->fetch(PDO::FETCH_ASSOC);
              ?>
                <tr>
                  <td class="py-1">
                    <img src="../profilePictures/<?php echo $data['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $data['id'] ?>)"><?php echo $data['username'] ?></span>
                  </td>
                  <td>
                    Currently Viewing <a class="web-page" href="<?php echo $row['page'] ?>"><strong><?php echo $row['page'] ?></strong></a>
                  </td>
                  <td>
                    <?php if ($admin) {?><span class="cTooltip"><i class='fas fa-siren-on'></i><b title="<?php echo $data['lastLoginIP']?>"></b></span> <?php } echo $row['times'] ?>
                  </td>
                  <td>
                    <button data-id="<?php echo $row['id']?>" class="logoutUser profile-close-button btn btn-danger mr-2">Logout</button>
                  </td>
                </tr>
                <?php
              }

            ?>
        </tbody>
      </table>
    </div>
<script>
$(".logoutUser").on("click", function () {
  var elem = $(this);
  $.ajax({
      type: 'POST',
      url: 'scripts/logoutUser.php?id=' + $(this).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "deleted") {
      elem.parent().parent().fadeOut();
      newSuccess("You have logged that user out!");
    } else if (response == "parker") {
      newError("Nice try but you can't log Parker out.");
    } else {
      newError("An unknown error occured.");
    }
  });
});
</script>
