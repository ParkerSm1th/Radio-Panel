<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Dashboard";
include('../../includes/header.php');
include('../../includes/config.php');
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
$now_id = $row['id'];
$type = $row['booked_type'];
if ($bookedUser != '0') {
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$bookedUser'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $now_dj = $row['username'];
  $now_dj_img = "../profilePictures/" . $row['avatarURL'];
  if ($type == '1') {
    $now_dj_span = "<span style='background: #0585FF; border-radius: 3px; padding: 3px 5px;'><i class='far fa-life-ring' style='font-size: 19px;'></i></span> <span onclick='loadProfile(" . $row['id'] . ")' class='userLink'>$now_dj</span>";
  } else {
    $now_dj_span = "<span onclick='loadProfile(" . $row['id'] . ")' class='userLink'>$now_dj</span>";
  }
  $now_booked = true;
} else {
  $now_dj = "Unbooked";
  $now_dj_img = null;
  $now_dj_span = "<span>Unbooked</span>";
  $now_booked = false;
}
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :hour ORDER BY id");
$stmt->bindParam(':day', $next_date, PDO::PARAM_INT);
$stmt->bindParam(':hour', $next_hour, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$bookedUser = $row['booked'];
$next_id = $row['id'];
if ($bookedUser != '0') {
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$bookedUser'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $next_dj = $row['username'];
  $next_dj_img = "../profilePictures/" . $row['avatarURL'];
  $next_dj_span = "<span onclick='loadProfile(" . $row['id'] . ")' class='userLink'>$next_dj</span>";
  $next_booked = true;
} else {
  $next_dj = "Unbooked";
  $next_dj_img = null;
  $next_dj_span = "<span>Unbooked</span>";
  $next_booked = false;
}
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :hour ORDER BY id");
$stmt->bindParam(':day', $later_date, PDO::PARAM_INT);
$stmt->bindParam(':hour', $later_hour, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$bookedUser = $row['booked'];
$later_id = $row['id'];
if ($bookedUser != '0') {
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$bookedUser'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $later_dj = $row['username'];
  $later_dj_img = "../profilePictures/" . $row['avatarURL'];
  $later_dj_span = "<span onclick='loadProfile(" . $row['id'] . ")' class='userLink'>$later_dj</span>";
  $later_booked = true;
} else {
  $later_dj = "Unbooked";
  $later_dj_img = null;
  $later_dj_span = "<span>Unbooked</span>";
  $later_booked = false;
}
$stmt = $conn->prepare("SELECT * FROM points WHERE user = :id");
$stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
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
<div class="row">
 <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
   <div class="card card-statistics">
     <div class="card-body">
       <div class="clearfix">
         <div class="float-left">
           <i style='font-size: 45px;' class="fas fa-user text-warning"></i>
         </div>
         <div class="float-right">
           <p class="mb-0 text-right">Current Listeners</p>
           <div class="fluid-container">
             <h3 class="font-weight-medium text-right mb-0"><span id='listeners'><i class="fas fa-circle-notch fa-spin" style="color: #fff; padding: 8px; font-size: 13px;"></i></span></h3>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
 <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
   <div class="card card-statistics">
     <div class="card-body">
       <div class="clearfix">
         <div class="float-left">
           <i style='font-size: 45px;' class="fas fa-users text-success"></i>
         </div>
         <div class="float-right">
           <p class="mb-0 text-right">This Slot's Peak</p>
           <div class="fluid-container">
             <h3 class="font-weight-medium text-right mb-0"><span id='listenersPeak'><i class="fas fa-circle-notch fa-spin" style="color: #fff; padding: 8px; font-size: 13px;"></i></span></h3>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
 <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
   <div class="card card-statistics">
     <div class="card-body">
       <div class="clearfix">
         <div class="float-left">
           <i style='font-size: 45px;' class="fas fa-exclamation text-danger"></i>
         </div>
         <div class="float-right">
           <p class="mb-0 text-right">Reputation</p>
           <div class="fluid-container">
             <h3 class="font-weight-medium text-right mb-0"><?php echo $total ?></h3>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
 <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
   <div class="card card-statistics">
     <div class="card-body">
       <div class="clearfix">
         <div class="float-left">
           <i style='font-size: 45px;' class="fas fa-clock text-info"></i>
         </div>
         <div class="float-right">
           <p class="mb-0 text-right">Booked Slots</p>
           <div class="fluid-container">
             <?php
             $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id ORDER BY id");
             $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
             $stmt->execute();
             $count = $stmt->rowCount();
              ?>
             <h3 class="font-weight-medium text-right mb-0"><?php echo $count ?></h3>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
<div class="row">
	<div class="col-md-4 grid-margin stretch-card">
		<div class="card" style="border-left: 2px solid #1b7305;">
			<div class="card-body">
				<div class="d-flex flex-row">
					<img id="img-<?php echo $now_id?>" src="<?php echo $now_dj_img ?>" onerror="this.src='../images/default.png'" class="mini-tt img-lg rounded" alt="profile image">
					<div class="mini-tt">
            <div id="now">
              <?php 
              if ($now_booked) {
                ?>
                  <h1 class="mt-2 mini-tt font-weight-bold"><?php echo $now_dj_span ?></h1>
                <?php
                } else {
                  ?>
                    <div style="margin: 0px;     margin-bottom: 4px;
    margin-top: 2px;" class="timetable-button timetable-button-u bookButton" data-id="<?php echo $now_id?>"><p>Cover</p></div>
                  <?php
                }?>
            </div>
						<p class="text-muted mini-tt"><?php echo $now_hour ?>:00 - <?php echo $next_hour ?>:00</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 grid-margin stretch-card">
		<div class="card" style="border-left: 2px solid #a9a40b;">
			<div class="card-body">
				<div class="d-flex flex-row">
          <img id="img-<?php echo $next_id?>" src="<?php echo $next_dj_img ?>" onerror="this.src='../images/default.png'" class="mini-tt img-lg rounded" alt="profile image">
          <div class="mini-tt">
            <div id="next">
            <?php 
              if ($next_booked) {
                ?>
                  <h1 class="mt-2 mini-tt font-weight-bold"><?php echo $next_dj_span ?></h1>
                <?php
                } else {
                  ?>
                    <div style="margin: 0px;     margin-bottom: 4px;
    margin-top: 2px;" class="timetable-button timetable-button-u bookButton" data-id="<?php echo $next_id?>"><p>Book</p></div>
                  <?php
                }?>
            </div>
            <p class="text-muted mini-tt"><?php echo $next_hour ?>:00 - <?php echo $later_hour ?>:00</p>
          </div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 grid-margin stretch-card">
		<div class="card" style="border-left: 2px solid #a20606;">
			<div class="card-body">
				<div class="d-flex flex-row">
          <img id="img-<?php echo $later_id?>" src="<?php echo $later_dj_img ?>" onerror="this.src='../images/default.png'" class="mini-tt img-lg rounded" alt="profile image">
          <div class="mini-tt">
          <div id="later">
            <?php 
              if ($later_booked) {
                ?>
                  <h1 class="mt-2 mini-tt font-weight-bold"><?php echo $later_dj_span ?></h1>
                <?php
                } else {
                  ?>
                    <div style="margin: 0px;     margin-bottom: 4px;
    margin-top: 2px;" class="timetable-button timetable-button-u bookButton" data-id="<?php echo $later_id?>"><p>Book</p></div>
                  <?php
                }?>
            </div>
            <p class="text-muted mini-tt"><?php echo $later_hour ?>:00 - <?php echo $later_hour + 1?>:00</p>
          </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
  <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12">
      <div class="card">
        <div class="card-head" style="padding-bottom: 0px;">
          <h1>Hourly Listener History <span class="cTooltip"><i class="fa fa-info-circle" style="font-size: 12px; color: #ccc;"></i><b title="This is the listener history from the last 12 hours"></b></span></h1>
        </div>
        <div class="card-body" style="    padding: 0px !important;
      margin-left: -9px;
      margin-bottom: -9px;
      overflow: hidden;">
          <canvas id="hourly" width="762px" height="200px" style=""></canvas>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-sm-12">
        <div class="card p-10-10">
          <div class="row">
            <div class="col-md-3">
              <a href="Staff.Rules" class="web-page">
                <div class=" quickLink">
                  <i class="fal fa-clipboard-list"></i>
                  <h1>Rules</h1>
                </div>
              </a>
            </div>
            <div class="col-md-3 b-l">
              <a href="Staff.Points" class="web-page">
                <div class="web-page quickLink">
                  <i class="fal fa-sort-circle-up"></i>
                  <h1>Points</h1>
                </div>
              </a>
            </div>
            <div class="col-md-3 b-l">
              <a href="Staff.Profile" class="web-page">
                <div class="quickLink">
                  <i class="fal fa-user"></i>
                  <h1>Profile</h1>
                </div>
              </a>
            </div>
            <div class="col-md-3 b-l">
              <a href="Staff.Reviews" class="web-page">
                <div class="quickLink">
                  <i class="fal fa-money-check-edit"></i>
                  <h1>Reviews</h1>
                </div>
              </a>
            </div>
          </div>
        </div>
        </div>
        <div class="col-md-6 col-sm-12">
          <div class="card p-10-10" style="background: #2989eb !important;">
            <div class="row">
              <div class="col-md-3">
                <a href="Radio.Rules" class="web-page">
                  <div class=" quickLink">
                    <i class="fal fa-clipboard-list"></i>
                    <h1>Rules</h1>
                  </div>
                </a>
              </div>
              <div class="col-md-3 b-l">
                <a href="Radio.Timetable" class="web-page">
                  <div class="web-page quickLink">
                    <i class="fal fa-calendar-week"></i>
                    <h1>Timetable</h1>
                  </div>
                </a>
              </div>
              <div class="col-md-3 b-l">
                <a href="Radio.Streamer" class="web-page">
                  <div class="quickLink">
                    <i class="fal fa-headset"></i>
                    <h1>Streamer</h1>
                  </div>
                </a>
              </div>
              <div class="col-md-3 b-l">
                <a href="Radio.Resources" class="web-page">
                  <div class="quickLink">
                    <i class="fal fa-cloud-download-alt"></i>
                    <h1>Resources</h1>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
    <div class="card">
      <div class="card-head" style="padding-bottom: 0px;">
        <h1>Daily Listener History <span class="cTooltip"><i class="fa fa-info-circle" style="font-size: 12px; color: #ccc;"></i><b title="This is the listener history from the past 7 days"></b></span></h1>
      </div>
      <div class="card-body" style="    padding: 0px !important;
    margin-left: -9px;
    margin-bottom: -9px;
    overflow: hidden;">
        <canvas id="daily" width="100%" height="200px" style=""></canvas>
      </div>
    </div>
    <div class="card p-10-10" style="height: 89px">
      <div class="stat lStat" style="left: 40px;">
        <i class="fas fa-headphones"></i>
        <h1 id="list"><i class="fas fa-circle-notch fa-spin" style="margin-top: -15px;"></i></h1>
      </div>
      <div class="stat dStat" style="left: 210px">
        <i class="fab fa-discord"></i>
        <h1 id="discCount"><i class="fas fa-circle-notch fa-spin" style="margin-top: -15px;;"></i></h1>
      </div>
    </div>
  </div>
</div>
<script>
clearInterval(pageInt); 
pageInt = null;
chart = null;
var pageInt = setInterval(updateStats, 10000);
setTimeout(updateStats, 500);
function updateStats(url) {
  return new Promise((resolve, reject) => {
		$("#listeners").load('./scripts/stats.php?specific=listeners', function() {
		  resolve("Success!");
    });
    $("#list").load('./scripts/stats.php?specific=listeners', function() {
		  resolve("Success!");
		});
    $("#listenersPeak").load('./scripts/stats.php?specific=listenersPeak', function() {
		  resolve("Success!");
    });
    $.ajax({
        type: 'GET',
        url: 'https://discordapp.com/api/guilds/704843392911409184/widget.json'
    }).done(function(response) {
        $("#discCount").html(response.presence_count);
    }).fail(function (response) {
       console.log('error');
    });
    updateGraph();
  });
}

$('.bookButton').click(function () {
  var object = $(this);
  var thing = this;
  var id = $(this).attr('data-id');
  object.html('<i class="fas fa-circle-notch fa-spin" style="color: #fff; padding: 8px; font-size: 13px;"></i>');
  $.ajax({
      type: 'POST',
      url: './scripts/bookSlot.php',
      data: {id: id}
  }).done(function(response) {
    console.log(response);
    if (response == 'booked') {
      $("#img-" + id).attr('src', "../profilePictures/<?php echo $_SESSION['loggedIn']['avatarURL']?>");
      object.parent().html(`<h1 class="mt-2 mini-tt font-weight-bold"><span onclick="loadProfile('<?php echo $_SESSION['loggedIn']['id']?>')" class='userLink'><?php echo $_SESSION['loggedIn']['username']?></span></h1>`);
    } else if (response == 'covered') {
      $("#img-" + id).attr('src', "../profilePictures/<?php echo $_SESSION['loggedIn']['avatarURL']?>");
      object.parent().html(`<h1 class="mt-2 mini-tt font-weight-bold"><span onclick="loadProfile('<?php echo $_SESSION['loggedIn']['id']?>')" class='userLink'><?php echo $_SESSION['loggedIn']['username']?></span></h1>`);
    } else {
      object.html('<p><i class="fas fa-times" style="color: #fff; font-size: 13px;"></i> Failed</p>');
      setTimeout()
    }
  }).fail(function (response) {
    object.html('<p><i class="fas fa-times" style="color: #fff; font-size: 13px;"></i> Failed</p>');
  });
});


var chart;
$.ajax({
  type: 'GET',
  url: 'scripts/listenerCount.php?type=d'
}).done(function (response) {
  var ctx = document.getElementById('hourly').getContext('2d');
  var data = JSON.parse(response);
  chart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: ['', '<?php echo date('H', time() - 43200);?>:00', '<?php echo date('H', time() - 39600);?>:00', '<?php echo date('H', time() - 36000);?>:00', '<?php echo date('H', time() - 32400);?>:00', '<?php echo date('H', time() - 28800);?>:00', '<?php echo date('H', time() - 25200);?>:00', '<?php echo date('H', time() - 21600);?>:00', '<?php echo date('H', time() - 18000);?>:00', '<?php echo date('H', time() - 14400);?>:00', '<?php echo date('H', time() - 10800);?>:00', '<?php echo date('H', time() - 7200);?>:00', '<?php echo date('H', time() - 3600);?>:00', '<?php echo date("H");?>:00', ''],
          datasets: [{
              label: 'Listeners',
              data: data,
              fill: true,
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
              pointRadius: [0,4,4,4,4,4,4,4,4,4,4,4,4,4,0],
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

var chart2;
$.ajax({
  type: 'GET',
  url: 'scripts/listenerCount.php?type=w'
}).done(function (response) {
  var ctx = document.getElementById('daily').getContext('2d');
  var data = JSON.parse(response);
  chart2 = new Chart(ctx, {
      type: 'line',
      data: {
          labels: ['', '<?php echo date('l', time() - 518400);?>', '<?php echo date('l', time() - 432000);?>', '<?php echo date('l', time() - 345600);?>', '<?php echo date('l', time() - 259200);?>', '<?php echo date('l', time() - 172800);?>', '<?php echo date('l', time() - 86400);?>', '<?php echo date('l', time());?>', ''],
          datasets: [{
              label: 'Listeners',
              data: data,
              fill: true,
              backgroundColor: '#2f3d99',
              borderColor: '#2f3d99',
              borderWidth: 2,
              borderCapStyle: 'butt',
              borderDash: [],
              borderDashOffset: 0.0,
              borderJoinStyle: 'miter',
              pointBorderColor: '#2f3d99',
              pointBackgroundColor: '#fff',
              pointBorderWidth: 2,
              pointHoverRadius: 4,
              pointHoverBackgroundColor: '#2f3d99',
              pointHoverBorderColor: '#fff',
              pointHoverBorderWidth: 2,
              pointRadius: [0,4,4,4,4,4,4,4,0],
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
    url: 'scripts/listenerCount.php?type=d'
  }).done(function (response) {
    var data = JSON.parse(response);
    chart.data.datasets[0].data = data;
    chart.update();
  });
}
</script>
