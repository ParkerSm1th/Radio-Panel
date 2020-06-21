<?php
session_start();
include('../includes/config.php');
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}
$stmt = $conn->prepare("SELECT * FROM requests ORDER BY id DESC LIMIT 1");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
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
if ($_GET['streamer'] == 1) {
  ?>
  <li>
    <div class="s-request">
      <div class="rq">
        <div class="type">
          <?php if ($row['type'] == 0) {
            ?>
              <span class="cTooltip"><i class="fas fa-user-music"></i><b title="Request"></b></span>
              <?php
          } else {
            ?>
              <span class="cTooltip"><i class="fas fa-user-tag"></i><b title="Shoutout"></b></span>
            <?php
          }?>
        </div>
        <div class="name">
          <p><strong><?php echo $row['name']?></strong></p>
        </div>
        <div class="msg">
          <p><?php if ($row['type'] == 0) {
            ?>
              <?php echo $row['artist'] ?> - <?php echo $row['song'] ?> <?php
              if ($row['message'] !== "") {
                ?> &bull;
                <?php echo $row['message'];
              }
          } else {
            ?>
              <?php echo $row['message']; ?>
            <?php
          }?></p>
        </div>
      </div>
      <div class="btns">
        <div class="tableButton" onclick="reportRequest(this);">
          <span class="cTooltip"><i id="reportRequest" data-id="<?php echo $row['id']?>" class="fas fa-flag"></i><b title="Report Request"></b></span>
        </div>
        <div class="tableButton" onclick="deleteRequest(this);" data-id="<?php echo $row['id']?>">
          <span class="cTooltip"><i id="deleteRequest" class="fas fa-trash"></i><b title="Delete Request"></b></span>
        </div>
      </div>
    </div>
  </li>
  <?php
  exit();
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
      <?php if ($admin) {
        ?>
        <span class="cTooltip"><i class='fas fa-siren-on'></i><b title="<?php echo $row['ip']?>"></b></span>
      <?php }
      echo $row['times'];
      ?>

    </td>
    <td>
      <?php
      if ($admin || $row['dj'] == $_SESSION['loggedIn']['id']) {
        ?>
        <div class="tableButton">
          <span class="cTooltip"><i id="reportRequest" data-id="<?php echo $row['id']?>" class="fas fa-flag"></i><b title="Report Request"></b></span>
        </div>
        <div class="tableButton" onclick="deleteRequest(this);" data-id="<?php echo $row['id']?>">
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

?>
