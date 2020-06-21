<?php
  $perm = 1;
  $media = 0;
  $radio = 1;
  $dev = 0;
  $title = "";
  include('../../includes/header.php');
  include('../../includes/config.php');
  date_default_timezone_set('Europe/London');
  $id = $_SESSION['loggedIn']['id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $currentSays = $row['djSays'];
  $date = date( 'N' ) - 1;
  $id = $_SESSION['loggedIn']['id'];
  $hour = date( 'H' );
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND booked = :id AND timestart = :hour");
  $stmt->bindParam(':id', $id);
  $stmt->bindParam(':day', $date);
  $stmt->bindParam(':hour', $hour);
  $stmt->execute();
  $count = $stmt->rowCount();
  $url = "http://31.220.56.47:3200/stats";
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_GET, true);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  $stats = json_decode($result, true);
  if ($result == false) {
    echo 'error';
  }
  if ($stats['success'] == true) {
    $currentDJ = $stats['currentDJ']['id'];
  }

  if ($count == 0 && $currentDJ != $_SESSION['loggedIn']['id'] && $_SESSION['loggedIn']['permRole'] < 4) {
    ?>
    <script>
     if (urlRoute.currentCode == "/Radio.Streamer") {
       urlRoute.loadPage("Staff.Dashboard");
       newError("You are not the current DJ!");
     }
    </script>
    <?php
    exit();
  }
  date_default_timezone_set('Europe/London');
  $date = date( 'N' ) - 1;
  $today_date = $date;
  $next_date = $date;
  $later_date = $date;

  $now_hour = date( 'H' ) ;
  $next_hour = date('H') + 1;
  $later_hour = $next_hour + 1;
  if ($next_hour == "24") {
    if ($date == 6) {
      $newDay = 0;
    } else {
      $newDay = $date + 1;
    }
    $next_date = $newDay;
    $next_hour = 0;
    $later_date = $newDay;
    $later_hour = 1;
  }
  if ($later_hour == "24") {
    if ($date == 6) {
      $newDay = 0;
    } else {
      $newDay = $date + 1;
    }
    $later_date = $newDay;
    $later_hour = 0;
  }
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :hour ORDER BY id");
  $stmt->bindParam(':day', $today_date, PDO::PARAM_INT);
  $stmt->bindParam(':hour', $now_hour, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $bookedUser = $row['booked'];
  if ($bookedUser != '0') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$bookedUser'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $now_dj = $row['username'];
    $now_dj_img = "../profilePictures/" . $row['avatarURL'];
    $now_dj_span = "<span onclick='loadProfile(" . $row['id'] . ")' class='userLink'>$now_dj</span>";
  } else {
    $now_dj = "Unbooked";
    $now_dj_img = null;
    $now_dj_span = "<span>Unbooked</span>";
  }
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :hour ORDER BY id");
  $stmt->bindParam(':day', $next_date, PDO::PARAM_INT);
  $stmt->bindParam(':hour', $next_hour, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $bookedUser = $row['booked'];
  if ($bookedUser != '0') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$bookedUser'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_dj = $row['username'];
    $next_dj_img = "../profilePictures/" . $row['avatarURL'];
    $next_dj_span = "<span onclick='loadProfile(" . $row['id'] . ")' class='userLink'>$next_dj</span>";
    $next_dj_discord = $row['discord'];
  } else {
    $next_dj = "Unbooked";
    $next_dj_img = "../images/square.png";
    $next_dj_span = "<span>Unbooked</span>";
    $next_dj_discord = 0;
  }
?>
<style>
.streamer {
  position: absolute;
  height: 100vh;
  width: 100vw;
  top: 0px;
  left: 0px;
  background: #0c1b2d;
  z-index: 100;
  overflow: scroll;
}

.loading {
  opacity: 1 !important;
}

.navbar-brand-wrapper {
  opacity: 0;
  z-index: -1;
}

.navbar-nav {
  position: absolute;
  right: 15px;
}

.navbar.default-layout {
  background: #021b3e !important;
}

.navbar {
  z-index: -1;
}
.top {
  width: 80%;
}
.controls {
  width: 200px;
  height: 63px;
  margin-left: 20px;
  margin-top: 11px;
}
.s-btn {
  height: 35px;
  width: 35px;
  background: #17273e;
  border-radius: 5px;
  display: inline-block;
  text-align: center;
  cursor: pointer;
}
.s-btn i {
  color: #fff;
  font-size: 23px;
  position: absolute;
  top: 17px;
  left: 31px;
}
.s-name {
  position: relative;
  width: 150px;
  height: 35px;
  font-size: 21px;
  font-weight: 500;
  display: inline-block;
  color: #fff;
}
.s-name p {
  font-size: 20px;
  position: absolute;
  top: 3px;
  left: 3px;
}
.update-boxes {
    position: absolute;
    top: 10px;
    width: 690px;
    left: 50%;
    transform: translateX(-50%);
    height: 160px;
}
.metric {
  background: #031327;
  padding: 5px 10px;
  border-radius: 3px;
  height: 61px;
}
.metric h1 {
  color: #fff;
  font-size: 25px;
}
.metric p {
  color: #ffffffc2;
  margin-bottom: 0px;
  margin-top: -6px;
}
.main {
  text-align: center;
  margin: 20px 60px;
}
.s-box {
    background: #17273e;
    border-radius: 3px;
    z-index: -1;
    margin-bottom: 20px;
}

.s-box-title {
    border-bottom: 0.5px solid #102035
}

.s-box-title h1 {
    color: #fff;
    font-size: 20px;
    text-align: left;
    padding-left: 10px;
    /* font-weight: 600; */
    padding-top: 11px;
}
.s-request {
  padding-top: 15px;
}
.rq {
  text-align: left;
  padding-left: 12px;
  display: inline-block;
  width: 79%;
  overflow-wrap: break-word;
}
.type {
  display: inline-block;
}
.name {
  display: inline-block;
  color: #fff;
  padding-left: 5px;
}
.msg {
  display: inline-block;
  color: #fff;
  padding-left: 5px;
}
.btns {
  display: inline-block;
  width: 20%;
  text-align: right;
  padding-right: 5px;
}
ul {
  list-style: none;
  padding-left: 0px;
}
.cTooltip b {
  color: #fff;
}
.cTooltip:hover b:after {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: -5px;
}
.streamForm {
  text-align: left;
  padding: 13px;
  margin-top: 10px;
}
.container-scroller {
  overflow: hidden;
  height: 100vh;
}
.submit {
  padding-bottom: 20px;
  padding-left: 15px;
  margin-top: -20px;
  text-align: center;
}
#errorFieldOutSays {
  margin-bottom: -20px;
}
.nextDJ {
  padding: 20px;
}
.nextDJ img {
  border-radius: 100%;
  height: 80px;
  width: 80px;
}
.nextDJ h1 {
  color: #fff;
  padding-top: 7px;
}
.nextDJ p {
  color: #ffffffc7;
  font-size: 18px;
}
</style>
<div class="streamer">
  <nav style="background: #0c1b2d !important;" class="special-nav navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div style="background: transparent;border-bottom: none;" class="navbar-menu-wrapper d-flex align-items-center">
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="profile-text">Hello, <?php echo $_SESSION['loggedIn']['username'] ?></span>
              <img class="img-xs rounded-circle" src="../profilePictures/<?php echo $_SESSION['loggedIn']['avatarURL'] ?>" onerror="this.src='../images/default.png'" alt="Profile image">
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
  <div class="top">
    <div class="controls">
      <a href="Staff.Dashboard" class="web-page">
          <div class="s-btn">
          <i class="far fa-chevron-left"></i>
        </div>
      </a>
      <div class="s-name">
        <p>Streamer Panel</p>
      </div>
    </div>
    <div class="update-boxes">
      <div class="row">
        <div class="col-3">
          <div class="metric">
            <h1><span id='time'><i class="fas fa-circle-notch fa-spin" style="color: #fff; margin-top: 5px; font-size: 25px;"></i></span></h1>
            <p>Time Left</p>
          </div>
        </div>
        <div class="col-3">
          <div class="metric">
            <h1><span id='listeners'><i class="fas fa-circle-notch fa-spin" style="color: #fff; margin-top: 5px; font-size: 25px;"></i></span></h1>
            <p>Listeners</p>
          </div>
        </div>
        <div class="col-3">
          <div class="metric">
            <h1><span id='listenersPeak'><i class="fas fa-circle-notch fa-spin" style="color: #fff; margin-top: 5px; font-size: 25px;"></i></span></h1>
            <p>Peak</p>
          </div>
        </div>
        <div class="col-3">
          <div class="metric">
            <h1><span id='likes'><i class="fas fa-circle-notch fa-spin" style="color: #fff; margin-top: 5px; font-size: 25px;"></i></span></h1>
            <p>Likes</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="main">
    <div class="row">
      <div class="col-3">
          <div class="s-box">
            <div class="s-box-title">
              <h1>Stream Settings</h1>
            </div>
            <div class="s-box-main">
              <form class="forms-sample" id='editSetting' action="#">
                <div class="form-group streamForm" id='errorFieldOutSays' style='display: none;'>
                  <span class="btn btn-danger submit-btn btn-block" id='error'></span>
                </div>
                <div class="form-group streamForm">
                  <label for="value">DJ Says</label>
                  <input type="text" class="form-control" name='value' id="value" placeholder="Enter New DJ Says" value="<?php echo $currentSays ?>">
                </div>
                <div class="submit form-group streamForm">
                  <button class="btn btn-success mr-2" id='submit'>Submit</button>
                </div>
              </form>
            </div>
          </div>
          <div class="s-box">
            <div class="s-box-title">
              <h1>Next DJ</h1>
            </div>
            <div class="s-box-main">
              <div class="nextDJ">
                <img src="<?php echo $next_dj_img?>" onerror="this.src='../images/square.png'"></img>
                <h1><?php echo $next_dj_span?></h1>
                <?php
                  if ($next_dj_discord !== 0) {
                    ?>
                      <p><?php echo $next_dj_discord?></p>
                    <?php
                  }
                ?>
              </div>
            </div>
        </div>
      </div>
      <div class="col-9">
        <div class="row">
          <div class="col-6">
            <div class="s-box">
              <div class="s-box-title">
                <h1>Request Line</h1>
              </div>
              <div class="s-box-main">
                <ul id="requestTable">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM requests WHERE deleted = '0' AND reported = '0' ORDER BY id DESC");
                    $stmt->execute();
                    $count = $stmt->rowCount();
                      if ($count > 0) {
                      ?>
                      <h2 style="margin: auto;
          color: #ffffffe8;
          padding: 30px 0;
          text-align: center;
          display: none;" id="noRequests">No new requests 😊</h2>
                      <?php
                      foreach($stmt as $row) {
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
                                  <div class="tableButton reportRequest" data-id="<?php echo $row['id']?>">
                                    <span class="cTooltip"><i id="reportRequest" data-id="<?php echo $row['id']?>" class="fas fa-flag"></i><b title="Report Request"></b></span>
                                  </div>
                                  <div class="tableButton deleteRequest" data-id="<?php echo $row['id']?>">
                                    <span class="cTooltip"><i id="deleteRequest" class="fas fa-trash"></i><b title="Delete Request"></b></span>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <?php
                          }
                        } else {
                          ?>
                          <h2 style="margin: auto;
              color: #ffffffe8;
              padding-top: 30px;
              text-align: center; padding-bottom: 30px;" id="noRequests">No new requests 😊</h2>
                          <?php
                        }

                      ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="s-box">
              <div class="s-box-title">
                <h1>Listener History</h1>
              </div>
              <div class="s-box-main" style="    padding: 0px !important;
      margin-left: -9px;
      margin-bottom: -9px;
      overflow: hidden;">
                  <canvas id="myChart" width="100%" height="250px" style="padding: 0px;"></canvas>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
var latest = null;
clearInterval(pageInt);
pageInt = null;
chart = null;
var pageInt = setInterval(updateStreamerStats, 2000);
setTimeout(updateStreamerStats, 500);
function updateStreamerStats(url) {
  return new Promise((resolve, reject) => {
		$("#likes").load('./scripts/stats.php?specific=likes', function() {
		  resolve("Success!");
		});
    $("#time").load('./scripts/stats.php?specific=time', function() {
		  resolve("Success!");
		});
    $("#listeners").load('./scripts/stats.php?specific=listeners', function() {
		  resolve("Success!");
		});
    $("#listenersPeak").load('./scripts/stats.php?specific=listenersPeak', function() {
		  resolve("Success!");
		});
    checkNew();
    updateGraph();
  });
}
var chart;
$.ajax({
  type: 'GET',
  url: 'scripts/listenerCount.php?type=h'
}).done(function (response) {
  var ctx = document.getElementById('myChart').getContext('2d');
  var data = JSON.parse(response);
  chart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: ['', '-55', '-50', '-45', '-40', '-35', '-30', '-25', '-20', '-15', '-10', '-5', '0', ''],
          datasets: [{
              label: '# of Listeners',
              data: data,
              backgroundColor: '#0585FF',
              borderColor: '#0585FF',
              borderWidth: 2,
              borderCapStyle: 'butt',
              borderDash: [],
              borderDashOffset: 0.0,
              borderJoinStyle: 'miter',
              pointBorderColor: '#0585FF',
              pointBackgroundColor: '#fff',
              pointBorderWidth: 2,
              pointHoverRadius: 4,
              pointHoverBackgroundColor: '#0585FF',
              pointHoverBorderColor: '#fff',
              pointHoverBorderWidth: 2,
              pointRadius: [0,4,4,4,4,4,4,4,4,4,4,4,4,0],
              pointHitRadius: 10,
              spanGaps: false
          }]
      },
      options: {
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        tooltips: {
          callbacks: {
            title: function(tooltipItem, data) {
              return data['labels'][tooltipItem[0]['index']];
            },
            label: function(tooltipItem, data) {
              return data['datasets'][0]['data'][tooltipItem['index']] + ' listeners';
            },
            afterLabel: function(tooltipItem, data) {
              return '';
            }
          },
          backgroundColor: '#00000094',
          titleFontSize: 12,
          titleFontColor: '#fff',
          bodyFontColor: '#fff',
          bodyFontSize: 13,
          displayColors: false
        },
        layout: {
          padding: {
            top: 10
          }
        },
          scales: {
            yAxes: [{
                  ticks: {
                      display: false,
                      min: 0
                  },
                  gridLines: {
                    display: false
                  }
              }],
              xAxes: [{
                ticks: {
                    display: false
                },
                gridLines: {
                    display: false
                }
              }]
          }
      }
  });
});

function updateGraph() {
  $.ajax({
    type: 'GET',
    url: 'scripts/listenerCount.php?type=h'
  }).done(function (response) {
    var data = JSON.parse(response);
    chart.data.datasets[0].data = data;
    chart.update();
  });
}

function checkNew() {
  $.ajax({
      type: 'GET',
      url: 'scripts/newestRequest.php?streamer=1'
  }).done(function(response) {
    if (latest == null) {
      latest = response;
      return true;
    }
    if (latest !== response) {
      latest = response;
      $("#noRequests").fadeOut();
      $("#requestTable").prepend(latest);
    }
  });
}
function deleteRequest(elem) {
  $(elem).parent().parent().parent().fadeOut();
  $.ajax({
      type: 'POST',
      url: 'scripts/deleteRequest.php?id=' + $(elem).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "deleted") {
      newSuccess("You have deleted that request!");
    } else if (response == "empty") {
      newSuccess("You have deleted that request!");
      $("#noRequests").fadeIn();
    } else {
      newError("An unknown error occured.");
    }
  });
}
$(".deleteRequest").on("click", function () {
  $(this).parent().parent().parent().fadeOut();
  $.ajax({
      type: 'POST',
      url: 'scripts/deleteRequest.php?id=' + $(this).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "deleted") {
      $(this).parent().parent().parent().fadeOut();
      newSuccess("You have deleted that request!");
    } else if (response == "empty") {
      newSuccess("You have deleted that request!");
      $("#noRequests").fadeIn();
    } else {
      newError("An unknown error occured.");
    }
  });
});
function reportRequest(elem) {
  $(elem).parent().parent().parent().fadeOut();
  $.ajax({
      type: 'POST',
      url: 'scripts/reportRequest.php?id=' + $(elem).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "reported") {
      newSuccess("You have reported that request!");
    } else if (response == "empty") {
      newSuccess("You have reported that request!");
      $("#noRequests").fadeIn();
    } else {
      newError("An unknown error occured.");
    }
  });
}
$(".reportRequest").on("click", function () {
  $(this).parent().parent().parent().fadeOut();
  $.ajax({
      type: 'POST',
      url: 'scripts/reportRequest.php?id=' + $(this).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "reported") {
      $(this).parent().parent().parent().fadeOut();
      newSuccess("You have reported that request!");
    } else if (response == "empty") {
      newSuccess("You have reported that request!");
      $("#noRequests").fadeIn();
    } else {
      newError("An unknown error occured.");
    }
  });
});
var form = $('#editSetting');
$(form).submit(function(event) {
    var error = false;
    var errorMessage = '';
    event.preventDefault();
    console.log("Submitted");
    var formData = $(form).serialize();
    var value = $('#value');
    console.log(formData);

    if (error) {
      $('#errorFieldOutSays').fadeIn();
      $('#errorField').html(errorMessage);
      return true;
    }

    $.ajax({
        type: 'POST',
        url: 'scripts/setDJSays.php',
        data: formData
    }).done(function(response) {
      console.log(response);
      if (response == 'updated') {
        $('#errorFieldOutSays').fadeIn();
        $('#error').removeClass('btn-danger');
        $('#error').addClass('btn-success');
        $('#error').html('Success! Your DJ Says has been updated');
        setTimeout(function() {
          $('#errorFieldOutSays').fadeOut();
        }, 3000);
      } else {
        $('#error').addClass('btn-danger');
        $('#error').removeClass('btn-success');
        $('#errorFieldOutSays').fadeIn();
        $('#error').html('Unknown error occured..');
        setTimeout(function() {
          $('#errorFieldOutSays').fadeOut();
        }, 3000);
      }
    }).fail(function (response) {
        $('#errorFieldOutSays').fadeIn();
        $('#error').html('Unknown error occured.');
        setTimeout(function() {
          $('#errorFieldOutSays').fadeOut();
        }, 3000);
    });
  });
</script>
