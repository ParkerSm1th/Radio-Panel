<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Infinite Staff -> Login</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
  <link rel="stylesheet" href="vendors/iconfonts/puse-icons-feather/feather.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="https://itsinfinite.net/v2/_assets/logo.png" />
</head>

<body style="background: #000 !important;">
  <img src="https://itsinfinite.net/v2/_assets/logo.png" id="back">
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one" style="background: #000000 !important;">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto" style="text-align: center;" id='loader'>
            <i class="fas fa-circle-notch fa-spin fa-4x" style="color: #fff;"></i>
          </div>
          <div class="col-lg-4 mx-auto" style='display: none;' id='content'>
            <h1 class="pLogo">Infinite</h1>
            <div class="auto-form-wrapper">
              <div class="form-group" id='errorFieldOut' style='display: none;'>
                <span class="btn btn-danger submit-btn btn-block" id='errorField'>Login</span>
              </div>
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
                    <input name='password' type="password" id='password' class="form-control" placeholder="*********">
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
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  <script>
  setTimeout(load, 2000)
  function load() {
    $('#loader').hide();
    $('#content').fadeIn();
  }
  $(function() {

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
          error = true;
          errorMessage = 'Please fill in all fields';
        } else {
          $('#usernameIcon').addClass('fa-check-circle');
          $('#usernameIcon').removeClass('fa-times-circle');
        }
        if (password.val() == null || password.val() == "") {
          $('#passwordIcon').removeClass('fa-check-circle');
          $('#passwordIcon').addClass('fa-times-circle');
          error = true;
          errorMessage = 'Please fill in all fields';
        } else {
          $('#passwordIcon').addClass('fa-check-circle');
          $('#passwordIcon').removeClass('fa-times-circle');
        }

        if (error) {
          $('#errorFieldOut').fadeIn();
          $('#errorField').html(errorMessage);
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
            $('#errorFieldOut').fadeIn();
            $('#errorField').removeClass('btn-danger');
            $('#errorField').addClass('btn-success');
            $('#errorField').html('Success! Logging in..');
            $('#submit').html('Login');
            window.location = 'panel/Staff.Dashboard';
          } else if (response == 'error') {
            $('#errorField').addClass('btn-danger');
            $('#errorField').removeClass('btn-success');
            $('#errorFieldOut').fadeIn();
            $('#submit').html('Login');
            $('#errorField').html('Invalid Login Details..');
          } else if (response == 'suspend') {
            $('#errorField').addClass('btn-danger');
            $('#errorField').removeClass('btn-success');
            $('#errorFieldOut').fadeIn();
            $('#submit').html('Login');
            $('#errorField').html('Your account is pending..');
          }
        }).fail(function (response) {
            $('#errorFieldOut').fadeIn();
            $('#errorField').html('Unknown error occured.');
        });
      });
  });
  </script>
</body>

</html>
