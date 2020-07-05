<?php
$perm = 2;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Slacking DJs";
include('../../includes/header.php');
include('../../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 3) {
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
              Slots Booked
            </th>
            <th>
              Warned
            </th>
            <?php if ($admin) {?>
            <th>
              Actions
            </th>
            <?php
          }?>
          </tr>
        </thead>
        <tbody>
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
                  $min = 3;
                  if ($row['guest'] == 1) {
                    $min = 1;
                  }
                  if ($count < $min) {
                    $warnS = $conn->prepare("SELECT * from points WHERE user = :id ORDER BY id DESC LIMIT 1");
                    $warnS->bindParam(":id", $row['id']);
                    $warnS->execute();
                    $warn = $warnS->fetch(PDO::FETCH_ASSOC);
                    $firstday = date('d/m', strtotime("sunday -1 week"));
                    $reason = "Week of " . $firstday;
                    if ($warn['message'] == $reason) {
                      $warned = "<i class='fa fa-check'></i>";
                    } else {
                      $warned = "<i class='fa fa-times'></i>";
                    }
                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $row['id'] ?>)"><?php echo $row['username'] ?></span>
                        </td>
                        <td>
                          <?php echo $count ?>
                        </td>
                        <td id="warned-<?php echo $row['id']?>">
                          <?php echo $warned ?>
                        </td>
                        <?php if ($admin) {?>
                        <td>
                          <button data-id="<?php echo $row['id']?>" class="warnUser profile-close-button btn btn-danger mr-2">Warn</button>
                        </td>
                        <?php
                        }?>
                      </tr>
                    <?php
                  }
                }
              }
            ?>
        </tbody>
      </table>
    </div>
<script>
$(".warnUser").on("click", function () {
  var elem = $(this);
  $.ajax({
      type: 'GET',
      url: 'scripts/addPoint.php?min=1&user=' + elem.attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == 'added') {
      $("#warned-" + elem.attr('data-id')).html(`
        <i class='fa fa-check'></i>
      `);
      newSuccess("That has been warned.");
    } else {
      newError("An unknown error occured");
    }
  }).fail(function (response) {
    newError("An unknown error occured");
  });
});
</script>