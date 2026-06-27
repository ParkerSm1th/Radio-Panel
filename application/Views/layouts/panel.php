<?php

use RadioPanel\Core\Auth;
use RadioPanel\Core\Config;
use RadioPanel\Core\Gdpr;
use RadioPanel\Core\Security;
use RadioPanel\View\View;

require_once APP_INCLUDES . '/helpers.php';

$user = $user ?? Auth::user();
$year = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo View::e(Config::get('app.name', 'Radio Panel')); ?></title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo View::asset('vendor/iconfonts/mdi/css/materialdesignicons.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo View::asset('vendor/css/vendor.bundle.base.css'); ?>">
  <link rel="stylesheet" href="<?php echo View::asset('vendor/css/vendor.bundle.addons.css'); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="<?php echo View::asset('css/token-input.css'); ?>">
  <link rel="stylesheet" href="<?php echo View::asset('css/token-input-mac.css'); ?>">
  <link rel="stylesheet" href="<?php echo View::asset('css/pnotify.custom.css'); ?>?date=<?php echo $year; ?>">
  <link rel="stylesheet" href="<?php echo View::asset('css/style.css'); ?>?date=<?php echo $year; ?>">
  <link rel="stylesheet" href="<?php echo View::asset('css/custom.css'); ?>?date=<?php echo $year; ?>">
  <link rel="shortcut icon" href="<?php echo View::asset('images/favicon.svg'); ?>">
  <meta name="csrf-token" content="<?php echo View::e($csrfToken); ?>">
</head>
<style>
#searchDropdown { position:absolute;display:none;border-radius:0 0 5px 5px;right:0;width:180px;top:30px;padding-bottom:10px!important; }
.overlay { height:100vh;width:100vw;position:absolute;transition:opacity 1s;z-index:9000; }
.overlayDark { background:#0c1b2d; }
.overlayGradient { background:linear-gradient(38deg,rgba(38,36,66,.71),rgba(45,45,84,.55),rgba(28,83,165,.86)); }
@media (max-width:580px){ #searchInput{display:none!important;} }
</style>
<body>
  <div class="overlay overlayDark" style="display:<?php echo View::e($displayOverlay); ?>"></div>
  <div class="overlay overlayGradient" style="display:<?php echo View::e($displayOverlay); ?>"></div>
  <div class="container-scroller">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo web-page" href="Staff.Dashboard">
          <img src="<?php echo View::asset('images/KeyFMWords.png'); ?>" alt="">
        </a>
        <a class="navbar-brand brand-logo-mini web-page" href="Staff.Dashboard">
          <img src="<?php echo View::asset('images/all-white.png'); ?>" alt="">
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center">
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item">
            <div class="form-group" style="margin-bottom:0;position:relative;">
              <input type="search" class="form-control" style="width:180px;" placeholder="Search Here" id="searchInput">
              <div class="dropdown-menu dropdown-menu-right dropdownDark navbar-dropdown" id="searchDropdown"></div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="mdi mdi-bell"></i>
              <div id="notificationsCount"></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" id="notifications"></div>
          </li>
          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown">
              <span class="profile-text">Hello, <?php echo View::e($user['username']); ?></span>
              <img class="img-xs rounded-circle" src="<?php echo web_path('profilePictures/' . $user['avatarURL']); ?>" onerror="this.src='<?php echo View::asset('images/default.png'); ?>'" alt="">
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdownDark navbar-dropdown">
              <?php if ($hasDiscord) { ?>
                <a class="dropdown-item web-page" href="Staff.Profile">Manage Profile</a>
                <a class="dropdown-item web-page" href="Staff.ChangePass">Change Password</a>
                <a class="dropdown-item web-page" href="Staff.Points">Staff Points</a>
                <a class="dropdown-item" href="<?php echo View::url('logout'); ?>">Logout</a>
              <?php } else { ?>
                <a class="dropdown-item" href="<?php echo View::url('logout'); ?>">Logout</a>
              <?php } ?>
            </div>
          </li>
        </ul>
      </div>
    </nav>

    <?php if ($hasDiscord) { ?>
    <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <?php require APP_INCLUDES . '/navbar.php'; ?>
      </nav>
      <div class="main-panel">
        <h1 id="pageTitle" class="page-title m-l-25"><i class="fas fa-circle-notch fa-spin"></i></h1>
        <div class="content-wrapper" id="content"></div>
      </div>
    </div>
    <?php } else { ?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel" style="width:100%!important;">
        <div class="content-wrapper">
          <div class="card discordVerify"><div class="card-body text-center">
            <h3 class="text-white">Welcome!</h3>
            <p class="text-white">Link your Discord account to access the full panel.</p>
            <div id="discordArea"><button class="btn btn-success" id="linkDiscord">Link Discord</button></div>
          </div></div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>

  <div class="modal-overlay closed" id="modal-overlay"></div>
  <div class="modal closed" id="profileModal">
    <div class="modalHead"><button onclick="closeProfile()" class="btn btn-success close-button profile-close-button"><i class="fa fa-times"></i></button></div>
    <div class="modal-guts" id="profileModalContent"><div class="loadingProfile"><i class="fas fa-circle-notch fa-spin"></i><h1>Loading Profile..</h1></div></div>
  </div>

  <script src="<?php echo View::asset('vendor/js/vendor.bundle.base.js'); ?>"></script>
  <script src="<?php echo View::asset('vendor/js/vendor.bundle.addons.js'); ?>"></script>
  <script src="<?php echo View::asset('js/jquery.tokeninput.js'); ?>"></script>
  <script src="<?php echo View::asset('js/off-canvas.js'); ?>"></script>
  <script src="<?php echo View::asset('js/misc.js'); ?>"></script>
  <script src="<?php echo View::asset('js/dashboard.js'); ?>"></script>
  <script src="<?php echo View::asset('js/url-routing.js'); ?>"></script>
  <script src="<?php echo View::asset('js/pnotify.custom.js'); ?>"></script>
  <?php require APP_INCLUDES . '/pnotify.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    window.RadioPanelApp = {
      appPath: <?php echo Security::escapeJs($appPath); ?>,
      apiBase: <?php echo Security::escapeJs($apiBase); ?>,
      loginUrl: <?php echo Security::escapeJs(View::url('')); ?>
    };

    (function () {
      var appPath = window.RadioPanelApp.appPath.replace(/\/$/, '');
      var appBase = window.location.origin + appPath;
      urlRoute
        .setApiBase(window.RadioPanelApp.apiBase)
        .setFolderUrl(appPath)
        .setPreviousCode('Staff.Dashboard')
        .setBaseUrl(appBase + '/')
        .checkCurrent();
    })();

    function notifications() {
      $('#notifications').load(window.RadioPanelApp.apiBase + '/notifications');
      $('#notificationsCount').load(window.RadioPanelApp.apiBase + '/notificationCount');
    }
    notifications();
    setInterval(notifications, 5000);

    $(".overlay").css("opacity","0");
    setTimeout(function(){ $(".overlay").css("display","none"); }, 1000);

    function login(){ window.location = window.RadioPanelApp.loginUrl; }
    function refreshNav(){ $("#sidebar").load(window.RadioPanelApp.apiBase + '/navbar'); }

    function closeProfile(){ $("#profileModal,#modal-overlay").addClass("closed"); }
    function loadProfile(id){
      $("#profileModal,#modal-overlay").removeClass("closed");
      $('#profileModalContent').load(window.RadioPanelApp.apiBase + '/getProfile?id=' + id);
    }
    function loadDropDownProfile(id){ loadProfile(id); }
    function loadDropDownPage(url){ urlRoute.loadPage(url); }

    $("#searchInput").on('keyup', function(){
      var q = $(this).val();
      if (!q) { $("#searchDropdown").hide().html(''); return; }
      $("#searchDropdown").show().html('<a class="dropdown-item"><i class="fas fa-circle-notch fa-spin"></i></a>');
      $.get(window.RadioPanelApp.apiBase + '/findUser', { q: q }).then(function(resp){
        var results = JSON.parse(resp);
        if (!results.length) { $("#searchDropdown").html('<a class="dropdown-item">Nothing found</a>'); return; }
        var out = '';
        for (var i=0;i<results.length;i++) out += '<a class="dropdown-item" onclick="'+results[i].action+'">'+results[i].title+'</a>';
        $("#searchDropdown").html(out);
      });
    });
  </script>
  <?php Gdpr::renderCookieBanner(); ?>
</body>
</html>
