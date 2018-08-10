<?php
session_start();
if ($_SESSION['loggedIn']['username'] == null) {
  header("Location: ../index.php");
  exit();
}
include('includes/pnotify.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Infinite Staff -> Panel</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
  <link rel="stylesheet" href="../vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="../css/pnotify.custom.css?date=<?php echo date('Y'); ?>">
  <link rel="stylesheet" href="../css/style.css?date=<?php echo date('Y'); ?>">
  <link rel="stylesheet" href="../css/custom.css?date=<?php echo date('Y'); ?>">
  <link rel="shortcut icon" href="../images/Logo.png" />
</head>

<body>
  <div class="container-scroller">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php">
          <h1 class="pLogoTwo">Infinite</h1>
        </a>
        <a class="navbar-brand brand-logo-mini" href="index.php">
          <h1 class="pLogoTwo">Infinite</h1>
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center">
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="mdi mdi-bell"></i>
              <span class="count" id="notificationsCount">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown" id="notifications">

              <a class="dropdown-item" style="width: 250px;">
                <div class="dropdown-divider"></div>
                <p class="mb-0 font-weight-normal float-left">You have 0 new notifications
                </p>
                <div class="dropdown-divider"></div>
              </a>

            </div>
          </li>
          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="profile-text">Hello, <?php echo $_SESSION['loggedIn']['username'] ?></span>
              <img class="img-xs rounded-circle" src="<?php echo $_SESSION['loggedIn']['avatarURL'] ?>" onerror="this.src='../images/Logo.png'" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style='width: 200px;'>
              <a class="dropdown-item mt-2">
                Manage Profile
              </a>
              <a class="dropdown-item">
                Change Password
              </a>
              <a class="dropdown-item">
                Warnings
              </a>
              <a class="dropdown-item web-page" href="Staff.Logout">
                Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="user-wrapper">
                <div class="profile-image">
                  <img src="<?php echo $_SESSION['loggedIn']['avatarURL'] ?>" onerror="this.src='../images/Logo.png'" alt="profile image">
                </div>
                <div class="text-wrapper">
                  <p class="profile-name userLink" onclick="loadProfile(<?php echo $_SESSION['loggedIn']['id'] ?>)"><?php echo $_SESSION['loggedIn']['username'] ?></p>
                  <div>
                    <small class="designation text-muted"><?php
                    if ($_SESSION['loggedIn']['radio'] == '1') {
                      ?>
                      <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
                      <?php
                    }
                    if ($_SESSION['loggedIn']['media'] == '1') {
                      ?>
                      <span class="cTooltip"><i class='fa fa-newspaper'></i><b title="Media Reporter"></b></span>
                      <?php
                    }
                    if ($_SESSION['loggedIn']['trial'] == '1') {
                      ?>
                      <span class="cTooltip"><i class="fas fa-clipboard-list"></i><b title="Trial"></b></span>
                      <?php
                    }
                    if ($_SESSION['loggedIn']['developer'] == '1') {
                      ?>
                      <span class="cTooltip"><i class='fas fa-code'></i><b title="Developer"></b></span>
                      <?php
                    }
                    if ($_SESSION['loggedIn']['permRole'] == '2') {
                      ?>
                      <span class="cTooltip"><i class='far fa-eye'></i><b title="Senior Staff"></b></span>
                      <?php
                    }
                    if ($_SESSION['loggedIn']['permRole'] == '3') {
                      ?>
                      <span class="cTooltip"><i class='fas fa-cog'></i><b title="Manager"></b></span>
                      <?php
                    }
                    if ($_SESSION['loggedIn']['permRole'] == '4') {
                      ?>
                      <span class="cTooltip"><i class='fas fa-key'></i><b title="Administrator"></b></span>
                      <?php
                    }
                    if ($_SESSION['loggedIn']['permRole'] >= '5') {
                      ?>
                      <span class="cTooltip"><i class='fas fa-money-check'></i><b title="Owner"></b></span>
                      <?php
                    }
                     ?></small>
                  </div>
                </div>
              </div>
              <a href="Staff.Logout" class="web-page"><span class="btn btn-danger btn-block">Logout</button></a>
            </div>
          </li>
          <?php
            $permRole = $_SESSION['loggedIn']['permRole'];
            $radio = $_SESSION['loggedIn']['radio'];
            $developer = $_SESSION['loggedIn']['developer'];
            $media = $_SESSION['loggedIn']['media'];
            if ($permRole >= 1) {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-staff" aria-expanded="false" aria-controls="ui-staff">
                  <i class="menu-icon fa fa-user"></i>
                  <span class="menu-title">Staff</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-staff">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Staff.Dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Staff.Rules">Rules</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Staff.Warnings">My Warnings</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Staff.Contact">Staff Contact</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
            if ($permRole >= 1 && $radio == '1') {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-dj" aria-expanded="false" aria-controls="ui-dj">
                  <i class="menu-icon fa fa-microphone-alt"></i>
                  <span class="menu-title">Radio DJ</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-dj">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Radio.Requests">Requests</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Radio.Timetable">Book A Slot</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Radio.Slots">My Slots</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Radio.DJSays">Set DJ Says</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Radio.Rules">Radio Rules</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Radio.Connection">Connection Information</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Radio.Banned">Banned Songs</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
            if ($permRole >= 1 && $media == '1') {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-media" aria-expanded="false" aria-controls="ui-media">
                  <i class="menu-icon fa fa-newspaper"></i>
                  <span class="menu-title">Media Reporter</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-media">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Media.New">New Article</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Media.Articles">My Articles</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
            if ($permRole >= 2 && $media == '1') {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-meditor" aria-expanded="false" aria-controls="ui-meditor">
                  <i class="menu-icon fas fa-pen"></i>
                  <span class="menu-title">Media Editor</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-meditor">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Editor.Articles">All Articles</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Editor.Approve">Approve Articles</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
            if ($permRole >= 2 && $radio == '1') {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-hdj" aria-expanded="false" aria-controls="ui-hdj">
                  <i class="menu-icon far fa-eye"></i>
                  <span class="menu-title">Head DJ</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-hdj">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="HDJ.Warn">Issue Warning</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="HDJ.Warnings">All Warnings</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="HDJ.Songs">Song History</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="HDJ.Kick">Kick DJ</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
            if ($permRole >= 3) {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-manager" aria-expanded="false" aria-controls="ui-manager">
                  <i class="menu-icon fas fa-cog"></i>
                  <span class="menu-title">Manager</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-manager">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Manager.Warn">Issue Warning</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Manager.Warns">All Warnings</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Manager.Trialists">Trialists</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Manager.Staff">Manage Staff</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Manager.New">New Staff Member</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Manager.Requests">Manage Requests</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Manager.Connection">Edit Connection Info</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
            if ($permRole >= 4) {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-admin" aria-expanded="false" aria-controls="ui-admin">
                  <i class="menu-icon fas fa-key"></i>
                  <span class="menu-title">Administrator</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-admin">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Admin.Warn">Issue Warning</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Admin.Warns">All Warnings</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Admin.Notification">Send Global Notification</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Dev.ActiveUsers">Logged In Users</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Admin.Log">Panel Logs</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
            if ($developer == '1') {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-dev" aria-expanded="false" aria-controls="ui-dev">
                  <i class="menu-icon fas fa-code"></i>
                  <span class="menu-title">Developer</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-dev">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Dev.Notifications">Notifications</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Dev.Log">Panel Logs</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Dev.Timetable">Timetable</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Dev.ActiveUsers">Logged In Users</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Dev.Server">Radio Server</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Dev.Users">Manager Users</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
            if ($permRole >= 5) {
              ?>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-owner" aria-expanded="false" aria-controls="ui-owner">
                  <i class="menu-icon fas fa-money-check"></i>
                  <span class="menu-title">Ownership</span>
                  <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-owner">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                      <a class="nav-link web-page" href="Dev.Server">Radio Server</a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php
            }
          ?>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper" id="content">

        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="container-fluid clearfix">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018
              <a href="http://www.yeetdev.com" target="_blank">Parker Smith</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Made for infinite.
            </span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <div class="modal-overlay closed" id="modal-overlay"></div>
  <div class="modal closed" id="profileModal">
    <div class="modalHead">
      <h1>Profile</h1>
   		<button onclick="closeProfile()" class="btn btn-success close-button profile-close-button" id="profile-close-button"><i class="fa fa-times"></i></button>
      </div>
  <div class="modal-guts" id='profileModalContent'>
    <h1>Loading Profile..</h1>

  </div>
</div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="../vendors/js/vendor.bundle.base.js"></script>
  <script src="../vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../js/off-canvas.js"></script>
  <script src="../js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../js/dashboard.js"></script>
  <!-- End custom js for this page-->
  <script type="text/javascript" src="urlRouting.js"></script>
  <script type="text/javascript" src="../js/pnotify.custom.js"></script>
  <script>
    urlRoute
      .folderUrl('/newPanel3939d/panel')
      .setPreviousCode('Staff.Dashboard')
      .setBaseUrl('http://infiniteradio.net/newPanel3939d/panel')
      .checkCurrent('http://infiniteradio.net/newPanel3939d/panel');
    notifications();
    var timeoutno = setInterval(notifications, 5000);
    function notifications () {
      $('#notifications').load('scripts/notifications.php');
      $('#notificationsCount').load('scripts/notificationCount.php');
    }


    function login() {
      window.location = 'http://infiniteradio.net/_$new$panel_';
    }

    var profileModal = document.querySelector("#profileModal");
    var modalOverlay = document.querySelector("#modal-overlay");
    var profileCloseButton = document.querySelector(".profile-close-button");

    function closeProfile() {
      profileModal.classList.toggle("closed");
      modalOverlay.classList.toggle("closed");
      $('#profileModalContent').html("<h1>Loading Profile..</h1>");
    }


    function loadProfile (id) {
      profileModal.classList.remove("closed");
      modalOverlay.classList.remove("closed");
      $('#profileModalContent').load('./scripts/getProfile.php?id=' + id);
    }
  </script>
</body>

</html>
