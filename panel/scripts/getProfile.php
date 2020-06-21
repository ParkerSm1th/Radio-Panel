<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}
if ($_GET['id'] == null) {
  echo "error";
} else {
  $id = $_GET['id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row['username'] == null) {
    ?>
    <div class="loadingProfile">
        <i class="fas fa-times" style="color: #fff; padding: 8px; font-size: 35px;"></i>
        <h1>Invalid User</h1>
      </div>
    </div>
    <?php
    return true;
    exit();
  }
  if ($row['region'] == "Global") {
    $region = '<span class="cTooltip"><i class="fas fa-globe-americas"></i><b title="Global"></b></span>';
  } else {
    $region = $row['region'];
  }
  if ($row['lastLogin'] == null) {
    $lastLogin = 'Never';
  } else {
    $lastLogin = $row['lastLogin'];
  }
  if ($row['discord'] == null) {
    $discord = 'None Set.';
  } else {
    $discord = $row['discord'];
  }
  ?>
   <style>
      .cTooltip b {
        bottom: 30px;
        z-index: 100 !important;
        margin: auto;
        position: absolute;
        display: block;
        z-index: 98;
        font-size: 12px;
        transition: all 500ms ease-in-out;
        text-align: center;
        left: -1000px;
        right: -1000px;
      }
    </style>
    <div class="profile">
    <div class="profileHeader">
      <div class="profileImage" style='padding-bottom: 10px;'>
        <img src="../profilePictures/<?php echo $row['avatarURL'] ?>" style='border-radius: 100%' onerror="this.src='../images/default.png'">
      </div>
      <div class="disc">
        <span><?php echo $discord ?></span>
      </div>
      <div class="name">
        <?php
          if ($row['inactive'] == "true") {
            ?>
              <h1>Pending - <?php echo $row['username'] ?></h1>
            <?php
          } else {
            ?>
              <h1><?php echo $row['username'] ?> <span style="font-size: 12px;"><?php
                if ($_SESSION['loggedIn']['developer'] == 1) {
                  echo "(" . $row['id'] . ")";
                }
              ?></span></h1>
            <?php
          }
        ?>
        <h2><?php
        if ($row['radio'] == '1') {
          ?>
          <span class="cTooltip"><i class='dstaff-text fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
          <?php
        }
        if ($row['media'] == '1') {
          ?>
          <span class="cTooltip"><i class='dstaff-text fa fa-newspaper'></i><b title="News Reporter"></b></span>
          <?php
        }
        if ($row['social'] == '1') {
          ?>
          <span class="cTooltip"><i class='dstaff-text fa fa-share-alt'></i><b title="Media Reporter"></b></span>
          <?php
        }
        if ($row['developer'] == '1') {
          ?>
          <span class="cTooltip"><i class='dstaff-text fas fa-code'></i><b title="Developer"></b></span>
          <?php
        }
        if ($row['permRole'] == '2') {
          ?>
          <span class="cTooltip"><i class='sstaff-text far fa-eye'></i><b title="Senior Staff"></b></span>
          <?php
        }
        if ($row['permRole'] == '3') {
          ?>
          <span class="cTooltip"><i class='manager-text fas fa-cog'></i><b title="Manager"></b></span>
          <?php
        }
        if ($row['permRole'] == '4') {
          ?>
          <span class="cTooltip"><i class='admin-text fas fa-key'></i><b title="Administrator"></b></span>
          <?php
        }
        if ($row['permRole'] == '5') {
          ?>
          <span class="cTooltip"><i class='executive-text fas fa-briefcase'></i><b title="Executive"></b></span>
          <?php
        }
        if ($row['permRole'] == '6') {
          ?>
          <span class="cTooltip"><i class='owner-text fas fa-money-check'></i><b title="Owner"></b></span>
          <?php
        }
        if ($row['inactive'] == 'true') {
          ?>
          <span class="cTooltip"><i class='fas fa-circle-notch'></i><b title="Pending/Suspended"></b></span>
          <?php
        }
        if ($row['trial'] == '1') {
          ?>
          <span class="cTooltip"><i class="fas fa-clipboard-list"></i><b title="Trial"></b></span>
          <?php
        }
        ?></h2>

       </div>

      <ul id="tabs" class="nav nav-tabs">
        <li class="active"><a href="#details">Details</a></li>
        <?php if ($_SESSION['loggedIn']['permRole'] >= 2) {
          ?>
            <li><a href="#rep">Reputation</a></li>
          <?php
        }?>
        <?php if ($row['radio'] == '1') {
          ?> 
          <li><a href="#radio">Radio</a></li> 
          <?php
        } ?>
        <?php if ($row['media'] == '1') {
          ?> 
          <li><a href="#media">Media</a></li> 
          <?php
        } ?>
      </ul>
    </div>
    <div class="tab-content">
        <div id="details" class="tab-pane in active show">
          <div class="details">
            <p>Joined us on the <span><?php echo $row['hired'] ?></span></p>
            <p>Region: <span><?php echo $region ?></span></p>
            <p>Discord: <span><?php echo $discord ?></span></p>
            <?php
            if ($_SESSION['loggedIn']['permRole'] >= 3) {?>
            <p>Last Login: <span><?php if ($admin) {?><span class="cTooltip"><i class='fas fa-siren-on'></i><b title="<?php echo $row['lastLoginIP']?>"></b></span> <?php } echo $lastLogin ?></span></p>
            <?php } ?>
          </div>
        </div>
        <?php if ($_SESSION['loggedIn']['permRole'] >= 2) {
          ?>
            <?php
              $id = $row['id'];
              $stmt = $conn->prepare("SELECT * FROM points WHERE user = :id");
              $stmt->bindParam(':id', $id);
              $stmt->execute();
              $count = $stmt->rowCount();
              $total = 0;
              foreach($stmt as $row) {
                if ($row['type'] == 0) {
                  $total = $total + $row['value'];
                } else if ($row['type'] == 1) {
                  $total = $total - $row['value'];
                }
              }
            ?>
            <div id="rep" class="profileTab tab-pane">
              <h3>Overall Reputation</h3>
              <?php
                if ($total > 0) {
                  $class = "positive";
                  $sign = "+";
                } else if ($total < 0) {
                  $class = "negative";
                }
              ?>
              <div class="pointsValueProf <?php echo $class ?>">
                <p><?php echo $sign . $total?></p>
              </div>
              <div style="margin-left: 24px;">
                <a class="web-page" onclick="closeProfile()"><button id='gRep' class="profile-close-button btn btn-dark mr-2">Add Point</button></a>
                <a class="web-page" onclick="closeProfile()"><button id='vRep' class="profile-close-button btn btn-dark mr-2">Point History</button></a>
              </div>
            </div>
          <?php
        }?>
        <?php
          $id = $_GET['id'];
          $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
          $stmt->bindParam(':id', $id);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

        ?>
        <?php if ($row['radio'] == '1') {
          ?>
          <?php
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id AND booked_type = 0");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $booked = $stmt->rowCount();
            $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id AND booked_type = 1");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $covered = $stmt->rowCount();
            $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $total = $stmt->rowCount();
            $stmt = $conn->prepare("SELECT * FROM likes WHERE dj = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $likes = $stmt->rowCount();
          ?>
          <div id="radio" class="profileTab tab-pane">
            <h3>Radio Stats</h3>
            <div class="row" style="margin: 20px 30px;">
              <div class="col-md-3">
                <h4>Booked Slots</h4>
                <div class="pointsValueProf ">
                  <p><?php echo $booked ?></p>
                </div>
              </div>
              <div class="col-md-3">
                <h4>Covered Slots</h4>
                <div class="pointsValueProf ">
                  <p><?php echo $covered ?></p>
                </div>
              </div>
              <div class="col-md-3">
                <h4>Total Slots</h4>
                <div class="pointsValueProf positive">
                  <p><?php echo $total ?></p>
                </div>
              </div>
              <div class="col-md-3">
                <h4>Likes</h4>
                <div class="pointsValueProf negative">
                  <p><?php echo $likes ?></p>
                </div>
              </div>
            </div>
          </div>
          <?php
        } ?>
        <?php if ($row['media'] == '1') {
          ?> 
          <div id="media" class="profileTab tab-pane ">
            <h3>Coming Soon</h3>
            <p>Media is not released yet..</p>
          </div>
          <?php
        } ?>
      </div>

      <?php
        if ($_SESSION['loggedIn']['permRole'] >= 3) {
          ?>
          <div class="form-group profileButtons">
            <a class="web-page" onclick="closeProfile()"><button id='pEdit' class="profile-close-button btn btn-dark mr-2">Edit User</button></a>
            <a class="web-page" onclick="closeProfile()"><button id="pConnection" class="profile-close-button btn btn-dark mr-2">User Actions</button></a>
            <a class="web-page" onclick="closeProfile()"><button id="pDelete" class="profile-close-button btn btn-light web-page">Delete User</button></a>
          </div>
          <?php
        }
      ?>
    </div>
    <script>
     $(document).ready(function(){
        $(".nav-tabs a").click(function(){
          $(this).tab('show');
          $("#tabs li").removeClass('active');
          $(this).parent().addClass('active');
        });
      });
      $("#pEdit").on("click", function() {
          urlRoute.loadPage("Manager.EditUser?id=<?php echo $id ?>");
      });

      $("#vRep").on("click", function() {
          urlRoute.loadPage("HDJ.PointHistory?id=<?php echo $id ?>");
      });

      $("#gRep").on("click", function() {
          urlRoute.loadPage("HDJ.AddPoint?id=<?php echo $id ?>");
      });

      $("#pConnection").on("click", function() {
        urlRoute.loadPage("Manager.UserActions?id=<?php echo $id ?>");
      });

      $("#pDelete").on("click", function() {
          urlRoute.loadPage("Manager.DeleteUser?id=<?php echo $id ?>");
      });

      window.onkeydown = function( event ) {
          if ( event.keyCode == 27 ) {
              closeProfile();
          }
      };

      $("#modal-overlay").click(function() {
        closeProfile();
      });
    </script>
  <?php
}
 ?>
