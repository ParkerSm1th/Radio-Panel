<?php
$perm = 2;
$media = 0;
$radio = 1;
$dev = 0;
$title = "Song Log";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
    <div class="table-responsive">
      <table class="table usersTable">
        <thead>
          <tr>
            <th>
              Played by
            </th>
            <th>
              Song
            </th>
            <th>
              Timestamp
            </th>
          </tr>
        </thead>
        <tbody>
          <?php

            $stmt = $conn->prepare("SELECT * FROM song_log ORDER BY id DESC LIMIT 100");
            $stmt->execute();

            foreach($stmt as $row) {
              $id = $row['dj'];

              $user = $conn->prepare("SELECT * FROM users WHERE id = :id");
              $user->bindParam(':id', $id);
              $user->execute();
              $data = $user->fetch(PDO::FETCH_ASSOC);
              if ($data['permRole'] == 1) {
                $color = 'dstaff-text';
              }
              if ($data['permRole'] == 2) {
                $color = 'sstaff-text';
              }
              if ($data['permRole'] == 3) {
                $color = 'manager-text';
              }
              if ($data['permRole'] == 4) {
                $color = 'admin-text';
              }

              if ($data['permRole'] == 5 || $data['permRole'] == 6) {
                $color = 'owner-text';
              }
              if ($row['dj_name'] !== "Auto DJ") {
              ?>
                <tr>
                  <td class="py-1">
                    <img src="../profilePictures/<?php echo $data['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink <?php echo $color ?>" onclick="loadProfile(<?php echo $data['id'] ?>)"><?php echo $row['dj_name'] ?></span>
                  </td>
                  <td>
                    <?php echo $row['artist'] ?> - <?php echo $row['title'] ?>
                  </td>
                  <td>
                    <?php echo $row['times'] ?>
                  </td>
                </tr>
                <?php
              } else {
                ?>
                <tr>
                  <td class="py-1">
                    <span><strong>Auto DJ</strong></span>
                  </td>
                  <td>
                    <?php echo $row['artist'] ?> - <?php echo $row['title'] ?>
                  </td>
                  <td>
                    <?php echo $row['times'] ?>
                  </td>
                </tr>
                <?php
              }
            }

            ?>
        </tbody>
      </table>
    </div>

