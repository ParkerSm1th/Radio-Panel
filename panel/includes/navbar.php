<?php
session_start();
if ($_SESSION['loggedIn']['username'] == null) {
  header("Location: ../index.php?ref=" . $_SERVER['REQUEST_URI']);
  exit();
}
include('config.php');
$stmt = $conn->prepare("SELECT * FROM post_away WHERE user = :id AND status = 1");
$stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
$stmt->execute();
$count = $stmt->rowCount();
if ($count == 0) {
  $away = 0;
} else {
  $awayDet = $stmt->fetch(PDO::FETCH_ASSOC);
  $now = date('d-m-Y', time());
  $timestampSeconds = $awayDet['length'] / 1000;
  $done = date("d-m-Y", $timestampSeconds);
  $diff = strtotime($now) - strtotime($done); 
  $dateDiff = abs(round($diff / 86400)); 
  if ($dateDiff < 1) {
    $stmt = $conn->prepare("UPDATE post_away SET status = 3 WHERE user = :user ORDER BY id DESC LIMIT 1");
    $stmt->bindParam(':user', $_SESSION['loggedIn']['id']);
    $stmt->execute();
    ?>
    <script>
      refreshNav();
      urlRoute.loadPage("Staff.Dashboard");
    </script>
    <?php
    $away = 0;
    ?>
      <script>
        setTimeout(function () {
          newSuccess("Welcome back from being posted away!");
        }, 1000)
      </script>
    <?php
  } else {
    $away = 1;
    ?>
    <script>urlRoute.loadPage('Staff.PostAway');</script>
  <?php
  }
}
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
$stmt->execute();
$details = $stmt->fetch(PDO::FETCH_ASSOC);
$loggedIn = [
  "username" => $details['username'],
  "id" => $details['id'],
  "avatarURL" => $details['avatarURL'],
  "permRole" => $details['permRole'],
  "radio" => $details['radio'],
  "developer" => $details['developer'],
  "media" => $details['media'],
  "social" => $details['social'],
  "trial" => $details['trial'],
  "displayRole" => $details['displayRole'],
  "discord" => $details['discord'],
  "discordID" => $details['discord_id'],
  "postedAway" => $away,
  "pending" => $details['pending']
];
$_SESSION['loggedIn'] = $loggedIn;
?>
  <ul class="nav">
    <?php
    $id = $_SESSION['loggedIn']['id'];
    $stmt = $conn->prepare("SELECT * FROM post_away WHERE user = :id AND status = 1");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count == 0) {
      $permRole = $_SESSION['loggedIn']['permRole'];
      $radio = $_SESSION['loggedIn']['radio'];
      $dev = $_SESSION['loggedIn']['developer'];
      $media = $_SESSION['loggedIn']['media'];
      $social = $_SESSION['loggedIn']['social'];
      $stmt = $conn->prepare("SELECT * FROM nav_ranks WHERE permRole <= :role AND radio <= :radio AND media <= :media AND dev <= :dev AND social <= :social ORDER BY permRole");
      $stmt->bindParam(":role", $permRole);
      $stmt->bindParam(":radio", $radio);
      $stmt->bindParam(":media", $media);
      $stmt->bindParam(":dev", $dev);
      $stmt->bindParam(":social", $social);
      $stmt->execute();
      foreach($stmt as $row) {
        $page1 = explode('/', $_COOKIE['currentPage']);
        $page2 = explode('.', $page1[1]);
        if ($row['prefix'] == $page2[0]) {
          $expanded = 'true';
          $class = 'show';
        } else {
          $expanded = 'false';
          $class = '';
        }
        ?>
          <li class="nav-item mainLink">
            <a class="nav-link drop" data-toggle="collapse" href="#ui-<?php echo $row['id']?>" aria-expanded="<?php echo $expanded ?>" aria-controls="ui-<?php echo $row['id']?>">
              <div class="navOverlay <?php echo $row['class']?>-background"></div>
              <i class="menu-icon <?php echo $row['icon'] ?> <?php echo $row['class']?>-text"></i>
              <span class="menu-title"><?php echo $row['name'] ?></span>
            </a>
            <div class="collapse <?php echo $class ?>" id="ui-<?php echo $row['id']?>">
              <ul class="nav flex-column sub-menu">
              <?php
              $dev = $_SESSION['loggedIn']['developer'];
              $stmt = $conn->prepare("SELECT * FROM panel_pages WHERE nav_rank = :id AND dev <= :dev AND pending >= :pending ORDER BY position");
              $stmt->bindParam(":id", $row['id']);
              $stmt->bindParam(":dev", $dev);
              $stmt->bindParam(":pending", $_SESSION['loggedIn']['pending']);
              $stmt->execute();
              foreach($stmt as $row) {
                if ($row['dev'] == 1) {
                ?>
                  <li class="nav-item">
                    <a class="nav-link web-page" href="<?php echo $row['url']?>">&#187; <?php echo $row['name']?> <i style="margin-left: 3px;" class="fal fa-file-code"></i></a>
                  </li>
                <?php
                } else if ($row['redirect'] == 1) {
                  ?>
                  <li class="nav-item">
                    <a class="nav-link" target="_blank" href="<?php echo $row['url']?>">&#187; <?php echo $row['name']?> <i style="margin-left: 3px;" class="fas fa-external-link"></i></a>
                  </li>
                  <?php
                } else {
                  ?>
                  <li class="nav-item">
                    <a class="nav-link web-page" href="<?php echo $row['url']?>">&#187; <?php echo $row['name']?></a>
                  </li>
                  <?php
                }
              }?>
              </ul>
            </div>
          </li>
        <?php
      }
      $hasPermShow = false;
       $stmt = $conn->prepare("SELECT * FROM perm_shows ORDER BY id DESC");
       $stmt->execute();
       $count = $stmt->rowCount();
       foreach($stmt as $row) {
          $hostArr=explode(",", $row['hosts']); 
          foreach($hostArr as $var) {
            if ($_SESSION['loggedIn']['id'] == $var) {
              $hasPermShow = true;
            }
          }
        }
       if ($hasPermShow) {
        $page1 = explode('/', $_COOKIE['currentPage']);
        $page2 = explode('.', $page1[1]);
        if ("Perm" == $page2[0]) {
          $expanded = 'true';
          $class = 'show';
        } else {
          $expanded = 'false';
          $class = '';
        }
         ?>
            <li class="nav-item mainLink">
            <a class="nav-link drop" data-toggle="collapse" href="#ui-permShow" aria-expanded="<?php echo $expanded ?>" aria-controls="ui-permShow">
              <div class="navOverlay" style="background: #08b39d !important"></div>
              <i class="menu-icon fas fa-calendar-star" style="color: #08b39d;"></i>
              <span class="menu-title">Perm Shows</span>
            </a>
            <div class="collapse <?php echo $class ?>" id="ui-permShow">
              <ul class="nav flex-column sub-menu">
              <?php
              $dev = $_SESSION['loggedIn']['developer'];
              $stmt = $conn->prepare("SELECT * FROM panel_pages WHERE nav_rank = 30 ORDER BY position");
              $stmt->execute();
              foreach($stmt as $row) {
                if ($row['dev'] == 1) {
                ?>
                  <li class="nav-item">
                    <a class="nav-link web-page" href="<?php echo $row['url']?>">&#187; <?php echo $row['name']?> <i style="margin-left: 3px;" class="fal fa-file-code"></i></a>
                  </li>
                <?php
                } else if ($row['redirect'] == 1) {
                  ?>
                  <li class="nav-item">
                    <a class="nav-link" target="_blank" href="<?php echo $row['url']?>">&#187; <?php echo $row['name']?> <i style="margin-left: 3px;" class="fas fa-external-link"></i></a>
                  </li>
                  <?php
                } else {
                  ?>
                  <li class="nav-item">
                    <a class="nav-link web-page" href="<?php echo $row['url']?>">&#187; <?php echo $row['name']?></a>
                  </li>
                  <?php
                }
              }?>
              </ul>
            </div>
          </li>
         <?php
       }
    } else {
        ?>
          <li class="nav-item mainLink">
            <a class="nav-link drop" data-toggle="collapse" href="#ui-away" aria-expanded="true" aria-controls="ui-away">
              <div class="navOverlay"></div>
              <i class="menu-icon fa fa-user"></i>
              <span class="menu-title">Staff</span>
            </a>
            <div class="collapse show" id="ui-away">
              <ul class="nav flex-column sub-menu">
              <li class="nav-item">
                <a class="nav-link web-page" href="Staff.PostAway">&#187; Post Away</a>
              </li>
              </ul>
            </div>
          </li>
        <?php
    }
    ?>

  </ul>