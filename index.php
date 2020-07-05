<!DOCTYPE html>
<html lang="en">
<?php
  session_start();
  if ($_SESSION['loggedIn']['id'] !== null) {
    header("Location: /panel/Staff.Dashboard");
  }
  if (isset($_GET['ref'])) {
    $_SESSION['ref'] = $_GET['ref'];
    header("Location: index.php");
  }
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>KeyFM Staff -> Login</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/login.css">
  <link rel="shortcut icon" href="images/default.png" />
  <style>
    .btn {
      transition: all 300ms ease-in-out;
    }

    .btn-danger {
        color: #fff;
        background-color: #b52c2b !important;
        border-color: #b52c2b !important;
    }
  </style>
</head>

<body style="background: #0c1b2d !important;">
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-bg-1 auth-page">
      <div class="content-wrapper d-flex align-items-center auth theme-one" id="main">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto" style="text-align: center;" id='loader'>
            <i class="fas fa-circle-notch fa-spin fa-4x" style="color: #fff;"></i>
          </div>
          <h1 style="display: none" class="pLogo"><img src="images/white-words.png"></img></h1>
          <div id="content" style='display: none;'>
            <div class="row w-100">
                <div class="auto-form-wrapper">
                  <div class="form-group" id='errorFieldOut' style='display: none;'>
                    <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
                  </div>
                  <div class="row">
                    <div class="col-md-4 left" id="left" style="text-align: center;">
                      <img id="user-img" class="user-img" src="images/square.png">
                    </div>
                    <div class="col-md-8 right" id="right">
                      <form action="#" id='loginForm'>
                        <div class="form-group">
                          <label class="label">Username</label>
                          <div class="input-group">
                            <input name='username' type="text" id='username' class="form-control" placeholder="Username">
                            <div class="input-group-append">
                              <span class="input-group-text">
                                <i id='usernameIcon' class="far fa-check-circle"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="label">Password</label>
                          <div class="input-group">
                            <input name='password' type="password" id='password' class="form-control" placeholder="Password">
                            <div class="input-group-append">
                              <span class="input-group-text">
                                <i id='passwordIcon' class="far fa-check-circle"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <button id='submit' class="btn btn-primary submit-btn btn-block">Login</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div id="loginConfirm" style='opacity: 0;'>
            <div class="row w-100">
                <div class="auto-form-wrapper">
                  <div class="notSeen">
                      <img class="user-img" src="images/square.png">
                      <h1 id="text"></h1>
                  </div>
                </div>

              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  <script>
  setTimeout(load, 1000)
  function load() {
    $('#loader').hide();
    $('#content').fadeIn();
    $(".pLogo").fadeIn();
  }

  $("#username").on('keyup', function() {
    var query = $('#username').val();
    if (query == null || query == "") {
      $(".user-img").attr('src', 'images/square.png');
      $(".user-img").css("box-shadow", "rgb(70, 70, 70) 0px 0px 15px 0px");
      $(".user-img").css("border", "5px solid rgba(28, 139, 129, 0)");
      return true;
    }
    $.ajax({
        type: "GET",
        url: "panel/scripts/loginSearch.php?loginSearch=1&q=" + query
    })
    .then(function(resp) {
      var response = JSON.parse(resp);
      if (response == null) {
        $(".user-img").attr('src', 'images/square.png');
        $(".user-img").css("box-shadow", "rgb(70, 70, 70) 0px 0px 15px 0px");
        $(".user-img").css("border", "5px solid rgba(28, 139, 129, 0)");
      } else {
        $(".user-img").attr('src', `${response.img}`);
        $(".user-img").css("border", response.border);
        $(".user-img").css("box-shadow", response.shadow);
      }
    });
  });

  function capitalizeFLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1); 
  }

  var errorChange;
  function showError(message) {
    clearTimeout(errorChange);
    errorChange = null;
    $("#submit").addClass('btn-danger');
    $("#submit").html(message);
    errorChange = setTimeout(function() {
      $("#submit").removeClass('btn-danger');
      $("#submit").html("Login");
    }, 4000)
  }

  var form = $('#loginForm');
  $(form).submit(function(event) {
      var error = false;
      var errorMessage = '';
      event.preventDefault();
      console.log("Submitted");
      var formData = $(form).serialize();
      var username = $('#username');
      var password = $('#password');
      if (username.val() == null || username.val() == "") {
        $('#usernameIcon').removeClass('fa-check-circle');
        $('#usernameIcon').addClass('fa-times-circle');
        $('#usernameIcon').css("color", "#ca4241");
        error = true;
        errorMessage = 'Please fill in all fields';
      } else {
        $('#usernameIcon').addClass('fa-check-circle');
        $('#usernameIcon').removeClass('fa-times-circle');
        $("#usernameIcon").css("color", "#078e4a");
      }
      if (password.val() == null || password.val() == "") {
        $('#passwordIcon').removeClass('fa-check-circle');
        $('#passwordIcon').addClass('fa-times-circle');
        $('#passwordIcon').css("color", "#ca4241");
        error = true;
        errorMessage = 'Please fill in all fields';
      } else {
        $('#passwordIcon').addClass('fa-check-circle');
        $('#passwordIcon').removeClass('fa-times-circle');
        $('#passwordIcon').css("color", "#078e4a");
      }

      if (error) {
        showError(errorMessage);
        return true;
      }
      $('#submit').html('<i class="fas fa-circle-notch fa-spin" style="color: #fff;"></i>');

      $.ajax({
          type: 'POST',
          url: 'panel/scripts/login.php',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'good') {
          $('#errorFieldOut').fadeOut();
          $("#text").html(`Welcome back, ${capitalizeFLetter($("#username").val())}<br><i class="fas fa-circle-notch fa-spin" style="color: #fff; margin-top: 15px;"></i>`);
          $("#content").css("opacity", "0");
          setTimeout(function() {
            $("#loginConfirm").css("opacity", "1");
            $("#loginConfirm").css("z-index", "100");
          }, 200);
          $('#submit').html('Login');
          setTimeout(function() {
            $("#main").fadeOut();
          }, 3500);
          setTimeout(function () {
            <?php
              if (isset($_SESSION['ref'])) {
                ?>
                  window.location = '<?php echo $_SESSION['ref']?>?welcome=1';
                <?php
              } else {
                ?>
                  window.location = 'panel/Staff.Dashboard?welcome=1';;
                <?php
              }
            ?>
          }, 4000);
          
          
        } else if (response == 'discord') {
          $('#errorFieldOut').fadeOut();
          $("#text").html(`Please verify login on discord..<br><i class="fas fa-circle-notch fa-spin" style="color: #fff; margin-top: 15px;"></i>`);
          $("#content").css("opacity", "0");
          setTimeout(function() {
            $("#loginConfirm").css("opacity", "1");
            $("#loginConfirm").css("z-index", "100");
          }, 200);
          setInterval(function() {
            $.ajax({
                type: 'GET',
                url: 'panel/scripts/ipMatches.php'
            }).done(function(response) {
              if (response == 1) {
                $('#submit').html('Logging in..');
                $('#errorField').html('Success! IP Verified.');
                $(form).submit();
              }
            });
          }, 1000)
        } else if (response == 'error') {
          showError("Invalid Login Details..");
        } else if (response == 'suspend') {
          showError("Your account is suspended");
        }
      }).fail(function (response) {
        showError("Unknown error occured");
      });
    });
  </script>
</body>

</html>
