<?php
session_start();
if ($_SESSION['loggedIn']['username'] == null) {
  header("Location: ../index.php?ref=" . $_SERVER['REQUEST_URI']);
  exit();
}
include('includes/pnotify.php');
include('includes/config.php');
$hasDiscord = true;
if ($_SESSION['loggedIn']['discord'] == null) {
  $hasDiscord = false;
}
if (isset($_GET['welcome'])) {
  $displayOverlay = "block";
} else {
  $displayOverlay = "none";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>KeyFM Staff -> Panel</title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
  <link rel="stylesheet" href="../vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="../css/token-input.css">
  <link rel="stylesheet" href="../css/token-input-mac.css">
  <link rel="stylesheet" href="../css/pnotify.custom.css?date=<?php echo date('Y'); ?>">
  <link rel="stylesheet" href="../css/style.css?date=<?php echo date('Y'); ?>">
  <link rel="stylesheet" href="../css/custom.css?date=<?php echo date('Y'); ?>">
  <link rel="shortcut icon" href="../images/favicon.svg" />
</head>
<style>
#searchDropdown {
  position: absolute;
  display: none;
  border-radius: 0px 0px 5px 5px;
  right: 0;
  border: none;
  width: 180px;
  height: auto;
  padding-bottom: 10px !important;
  top: 30px;
}

.overlay {
  height: 100vh;
  width: 100vw;
  position: absolute;
  transition: all 1000ms ease-in-out;
  opacity: 1;
  z-index: 9000;
}

.overlayDark {
  background: rgb(12, 27, 45);
}

.overlayGradient {
  background: rgb(42,38,94);
  background: linear-gradient(38deg, rgba(38, 36, 66, 0.7147233893557423) 0%, rgba(45, 45, 84, 0.55) 47%, rgba(28, 83, 165, 0.8631827731092436) 100%);
}

@media screen and (max-width: 580px) {
  #searchInput {
    display: none !important;
  }
}
</style>
<body>
  <div class="overlay overlayDark" style="display: <?php echo $displayOverlay ?>"></div>
  <div class="overlay overlayGradient" style="display: <?php echo $displayOverlay ?>"></div>
  <div class="container-scroller">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo web-page" href="Staff.Dashboard">
          <img src="../images/KeyFMWords.png">
        </a>
        <a class="navbar-brand brand-logo-mini web-page" href="Staff.Dashboard">
          <img src="../images/all-white.png">
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center">
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item">
              <div class="form-group" style="margin-bottom: 0px !important; position: relative;">
                <input type="search" class="form-control" style="width: 180px; border: 1px solid #112d50 !important; background: #112d50 !important;" placeholder="Search Here" id="searchInput">
                <div class="dropdown-menu dropdown-menu-right dropdownDark navbar-dropdown" id="searchDropdown" aria-labelledby="SearchDropdown" style="">
         
                </div>
              </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="mdi mdi-bell"></i>
              <div id="notificationsCount">

              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown" id="notifications">

              <a class="dropdown-item" style="width: 250px;">
                <div class="dropdown-divider"></div>
                <p class="mb-0 font-weight-normal float-left">You have 0 new notifications</p>
                <div class="dropdown-divider"></div>
              </a>

            </div>
          </li>
          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="profile-text">Hello, <?php echo $_SESSION['loggedIn']['username'] ?></span>
              <img class="img-xs rounded-circle" src="../profilePictures/<?php echo $_SESSION['loggedIn']['avatarURL'] ?>" onerror="this.src='../images/default.png'" alt="Profile image">
            </a>
            <style>
              .dropdownDark {
                width: 200px;
                background: #112e4f;
                padding-bottom: 10px !important;
              }
              .dropdownDark .dropdown-item {
                color: #fff;
              }
              .dropdownDark .dropdown-item:hover {
                background: #012446;
              }
            </style>
            <?php if ($hasDiscord) {
              ?>
                <div class="dropdown-menu dropdown-menu-right dropdownDark navbar-dropdown" aria-labelledby="UserDropdown" style="padding-bottom: 10px !important">
                  <a class="dropdown-item web-page" style="color: #fff;" href="Staff.Profile">
                    Manage Profile
                  </a>
                  <a class="dropdown-item web-page" style="color: #fff;" href="Staff.ChangePass">
                    Change Password
                  </a>
                  <a class="dropdown-item web-page" style="color: #fff;" href="Staff.Points">
                    Staff Points
                  </a>
                  <a class="dropdown-item web-page" style="color: #fff;" href="Staff.Logout">
                    Logout
                  </a>
                </div>
              <?php
            } else {
              ?>
                <div class="dropdown-menu dropdown-menu-right dropdownDark navbar-dropdown" aria-labelledby="UserDropdown" style='width: 200px;'>
                  <a class="dropdown-item web-page" onclick="window.location='../index.php'" href="Staff.LogoutFast">
                    Logout
                  </a>
                </div>
              <?php
            } ?>

          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"><i class="fas fa-bars"></i></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <?php if ($hasDiscord) {
      ?>
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <?php include('includes/navbar.php'); ?>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <h1 id="pageTitle" class="page-title m-l-25"><i class="fas fa-circle-notch fa-spin"></i></h1>
        <div class="content-wrapper" id="content">

        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- main-panel ends -->
    </div>
  <?php } else {
    ?>
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <!-- partial -->
      <div class="main-panel" style="width: 100% !important;">
        <div class="content-wrapper">
          <div class="card discordVerify">
            <div class="card-body text-center">
              <h3 style="text-align: center" class="text-white">Welcome to KeyFM!</h3>
              <h4 style="text-align: center" class="text-white">The first step to getting you setup is linking your discord!</h4>
              <div id="discordArea">
                <button style="padding-top: 10px" class="btn btn-success" id="linkDiscord">Link My Discord</button>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- main-panel ends -->
    </div>
  <?php } ?>
    <!-- page-body-wrapper ends -->
  </div>
  <div class="modal-overlay closed" id="modal-overlay"></div>
  <div class="modal closed" id="profileModal">
    <div class="modalHead">
   		<button onclick="closeProfile()" class="btn btn-success close-button profile-close-button" id="profile-close-button"><i class="fa fa-times"></i></button>
      </div>
  <div class="modal-guts" id='profileModalContent'>
    <div class="loadingProfile">
      <i class="fas fa-circle-notch fa-spin" style="color: #fff; padding: 8px; font-size: 35px;"></i>
      <h1>Loading Profile..</h1>
    </div>
  </div>
</div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="../vendors/js/vendor.bundle.base.js"></script>
  <script src="../vendors/js/vendor.bundle.addons.js"></script>
  <script src="../js/jquery.tokeninput.js"></script>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    urlRoute
      .folderUrl('/panel')
      .setPreviousCode('Staff.Dashboard')
      .setBaseUrl('https://staff.keyfm.net/panel')
      .checkCurrent('https://staff.keyfm.net/panel');
    notifications();
    var timeoutno = setInterval(notifications, 5000);
    function notifications () {
      $('#notifications').load('scripts/notifications.php');
      $('#notificationsCount').load('scripts/notificationCount.php');
    }
    $(".overlay").css("opacity", "0");
    setTimeout(function() {
      $(".overlay").css("display", "none");
    }, 1000);

    function getCookie(cname) {
      var name = cname + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(';');
      for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }


    function login() {
      window.location = 'https://staff.keyfm.net/';
    }

    function refreshNav() {
      $("#sidebar").load('includes/navbar.php');
    }


    $("#linkDiscord").on("click", function() {
      var params = 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no, width=400,height=750,left=100,top=4000';

      let popUp = open('https://discordapp.com/api/oauth2/authorize?client_id=706667108930551899&scope=identify&response_type=code&redirect_uri=http%3A%2F%2Fvps.parkersmith.io%3A3200%2Fdiscord%2Fcallback&state=<?php echo $_SESSION['loggedIn']['id']?>', 'Link Discord', params);
      $("#discordArea").html(`
        <i class="fas fa-circle-notch fa-spin" style="color: #fff; margin-top: 30px; font-size: 35px;"></i>
        `);
      setInterval(function() {
        $.ajax({
            type: 'POST',
            url: 'scripts/checkDiscord.php'
        }).done(function(response) {
          if (response == 1) {
            popUp.close();
            $("#discordArea").html(`
              <i class="fas fa-check manager-text" style="margin-top: 30px; font-size: 35px;"></i>
              <h4 class="text-white" style="margin-top: 10px;">Success! You will now be logged out of the panel, log back in and check discord!</h4>
            `);
            setTimeout(function() {
              window.location = "logout.php";
            }, 2000)
          }
        });
      }, 1000)
    });

    var profileModal = document.querySelector("#profileModal");
    var modalOverlay = document.querySelector("#modal-overlay");
    var profileCloseButton = document.querySelector(".profile-close-button");

    function closeProfile() {
      profileModal.classList.add("closed");
      modalOverlay.classList.add("closed");
      $('#profileModalContent').html(`
        <div class="loadingProfile">
            <i class="fas fa-circle-notch fa-spin" style="color: #fff; padding: 8px; font-size: 35px;"></i>
            <h1>Loading Profile..</h1>
          </div>
        </div>
        `);
    }


    function loadProfile (id) {
      profileModal.classList.remove("closed");
      modalOverlay.classList.remove("closed");
      $('#profileModalContent').load('./scripts/getProfile.php?id=' + id);
    }

    function loadDropDownProfile(id) {
      $('#searchInput').val("");
      $("#searchDropdown").css("display", "none");
      $("#searchDropdown").html('');
      profileModal.classList.remove("closed");
      modalOverlay.classList.remove("closed");
      $('#profileModalContent').load('./scripts/getProfile.php?id=' + id);
    }

    function loadDropDownPage(url) {
      $('#searchInput').val("");
      $("#searchDropdown").css("display", "none");
      $("#searchDropdown").html('');
      urlRoute.loadPage(url);
    }

    $("#searchInput").on('keyup', function() {
      var query = $('#searchInput').val();
      if (query == "") {
        $("#searchDropdown").css("display", "none");
        $("#searchDropdown").html('');
        return;
      }
      $("#searchDropdown").css("display", "block");
      $("#searchDropdown").html(`
      <a class="dropdown-item web-page" style="color: #fff; text-align: center">
        <i class="fas fa-circle-notch fa-spin" style="color: #fff; padding: 8px; font-size: 13px;"></i>
      </a>
      `);
      $.ajax({
          type: "GET",
          url: "scripts/findUser.php?q=" + query
      })
      .then(function(response) {
        var results = JSON.parse(response);
        if (results.length == 0) {
          $("#searchDropdown").html(`
          <a class="dropdown-item web-page" style="color: #fff; text-align: center">
            Nothing found
          </a>
          `);
          return true;
        }
        let output = '';
        for (var i = 0; i < results.length; i++) {
          let result = results[i];
          output += `<a class="dropdown-item" style="color: #fff; font-size: 14px;text-align: center; cursor: pointer; padding: 8px;" onclick="${result.action}">
            ${result.title}
          </a>`;
        }
        $("#searchDropdown").html(output);

      });
    });
  </script>
</body>

</html>
