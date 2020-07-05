<?php
$perm = 2;
$media = 0;
$radio = 0;
$dev = 0;
$debug = 1;
$title = "Booking Logs";
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
              User/Username
            </th>
            <th>
              IP
            </th>
            <th>
              Time
            </th>
            <th>
              Action
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            $stmt = $conn->prepare("SELECT * FROM panel_log WHERE action LIKE '%Slot%' ORDER BY id DESC");
            $stmt->execute();

            foreach($stmt as $row) {
              $name = $row['name'];
              if (is_numeric($name)) {
                $user = $conn->prepare("SELECT * FROM users WHERE id = :id");
                $user->bindParam(':id', $row['name']);
                $user->execute();
                $data = $user->fetch(PDO::FETCH_ASSOC);
                ?>
                  <tr>
                    <td class="py-1">
                      <img src="../profilePictures/<?php echo $data['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $data['id'] ?>)"><?php echo $data['username'] ?></span>
                    </td>
                    <td>
                      <?php if ($admin) {?><span class="cTooltip"><i class='fas fa-siren-on'></i><b title="<?php echo $row['ip']?>"></b></span> <?php } echo hash('ripemd160', $row['ip']) ?>
                    </td>
                    <td>
                      <?php echo $row['times'] ?>
                    </td>
                    <td>
                      <?php echo $row['action'] ?>
                    </td>
                  </tr>
                  <?php
                } else {
                  ?>
                    <tr>
                      <td class="py-1">
                        <span><?php echo $row['name'] ?></span>
                      </td>
                      <td>
                        <?php if ($admin) {?><span class="cTooltip"><i class='fas fa-siren-on'></i><b title="<?php echo $row['ip']?>"></b></span> <?php } echo hash('ripemd160', $row['ip']) ?>
                      </td>
                      <td>
                        <?php echo $row['times'] ?>
                      </td>
                      <td>
                        <?php echo $row['action'] ?>
                      </td>
                    </tr>
                    <?php
                }
              }

            ?>
        </tbody>
      </table>
    </div>

