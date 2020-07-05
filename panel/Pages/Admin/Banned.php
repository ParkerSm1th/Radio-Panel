<?php
$perm = 4;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Banned IPs";
include('../../includes/header.php');
include('../../includes/config.php');
ini_set('')
 ?>
    <div class="card-actions">
      <a href="Mgmt.NewShort" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">Ban an IP</button>
      </a>
    </div>
    <div class="table-responsive">
      <table class="table usersTable">
        <thead>
          <tr>
            <th>
              IP
            </th>
            <th>
              Reason
            </th>
            <th>
              Times
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
            $stmt = $conn->prepare("SELECT * FROM blocked_ips ORDER BY id");
            $stmt->execute();

            foreach($stmt as $row) {
              $user = $conn->prepare("SELECT * FROM users WHERE id = :id");
              $user->bindParam(":id", $row['user']);
              $user->execute();
              $data = $user->fetch(PDO::FETCH_ASSOC);
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $row['ip']; ?>
                </td>
                <td>
                  <?php echo $row['reason'] ?>
                </td>
                <td>
                  <?php echo $row['times'] ?>
                </td>
                <td>
                  <img src="../profilePictures/<?php echo $data['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $data['id'] ?>)"><?php echo $data['username'] ?></span>
                </td>
                <td>
                  <div class="tableButton">
                    <span class="cTooltip"><i id="editIP" data-id="<?php echo $row['id']?>" class="fas fa-pencil"></i><b title="Edit Ban"></b></span>
                  </div>
                  <div class="tableButton">
                    <span class="cTooltip"><i id="deleteIP" data-id="<?php echo $row['id']?>" class="fas fa-trash"></i><b title="Delete Ban"></b></span>
                  </div>
                </td>
              </tr>
              <?php
            }

            ?>
        </tbody>
      </table>
    </div>


