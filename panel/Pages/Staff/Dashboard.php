<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 0;
include('../../includes/header.php');
include('../../includes/config.php');
date_default_timezone_set('Europe/London');
$date = date( 'N' ) - 1;
$today_date = $date;
$next_date = $date;

$now_hour = date( 'H' ) ;
$next_hour = date('H') + 1;
$later_hour = $next_hour + 1;
if ( $next_hour == 24 ) {
  $later_date = $today_date + 1;
  $later_hour = "0";
}
else {
  $later_date = $today_date;
}
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :hour ORDER BY id");
$stmt->bindParam(':day', $today_date);
$stmt->bindParam(':hour', $now_hour);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$bookedUser = $row['booked'];
if ($bookedUser != '0') {
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$bookedUser'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $now_dj = $row['username'];
  $now_dj_img = $row['avatarURL'];
  $now_dj_span = "<span onclick='loadProfile(1)' class='userLink'>$now_dj</span>";
} else {
  $now_dj = "Unbooked";
  $now_dj_img = null;
  $now_dj_span = "<span>Unbooked</span>";
}
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :hour ORDER BY id");
$stmt->bindParam(':day', $next_date);
$stmt->bindParam(':hour', $next_hour);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$bookedUser = $row['booked'];
if ($bookedUser != '0') {
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$bookedUser'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $next_dj = $row['username'];
  $next_dj_img = $row['avatarURL'];
  $next_dj_span = "<span onclick='loadProfile(1)' class='userLink'>$next_dj</span>";
} else {
  $next_dj = "Unbooked";
  $next_dj_img = null;
  $next_dj_span = "<span>Unbooked</span>";
}
$stmt = $conn->prepare("SELECT * FROM timetable WHERE day = :day AND timestart = :hour ORDER BY id");
$stmt->bindParam(':day', $later_date);
$stmt->bindParam(':hour', $later_hour);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$bookedUser = $row['booked'];
if ($bookedUser != '0') {
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$bookedUser'");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $later_dj = $row['username'];
  $later_dj_img = $row['avatarURL'];
  $later_dj_span = "<span onclick='loadProfile(1)' class='userLink'>$later_dj</span>";
} else {
  $later_dj = "Unbooked";
  $later_dj_img = null;
  $later_dj_span = "<span>Unbooked</span>";
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
             <h3 class="font-weight-medium text-right mb-0"><span id='listeners'><i class="fas fa-circle-notch fa-spin" style="color: #000; padding: 8px; font-size: 13px;"></i></span></h3>
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
           <p class="mb-0 text-right">Peak Listeners</p>
           <div class="fluid-container">
             <h3 class="font-weight-medium text-right mb-0">90</h3>
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
           <p class="mb-0 text-right">Warning Points</p>
           <div class="fluid-container">
             <h3 class="font-weight-medium text-right mb-0">0</h3>
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
		<div class="card">
			<div class="card-body">
				<div class="d-flex flex-row">
					<img src="<?php echo $now_dj_img ?>" onerror="this.src='../images/Logo.png'" class="img-lg rounded" alt="profile image">
					<div class="ml-3">
						<h4 class="text-success">Now</h4>
						<p class="text-muted"><?php echo $now_hour ?>:00 - <?php echo $next_hour ?>:00</p>
						<p class="mt-2 font-weight-bold"><?php echo $now_dj_span ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<div class="d-flex flex-row">
					<img src="<?php echo $next_dj_img ?>" onerror="this.src='../images/Logo.png'" class="img-lg rounded" alt="profile image">
					<div class="ml-3">
						<h4 class="text-warning">Next</h4>
						<p class="text-muted"><?php echo $next_hour ?>:00 - <?php echo $later_hour ?>:00</p>
						<p class="mt-2 font-weight-bold"><?php echo $next_dj_span ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<div class="d-flex flex-row">
					<img src="<?php echo $later_dj_img ?>" onerror="this.src='../images/Logo.png'" class="img-lg rounded" alt="profile image">
					<div class="ml-3">
						<h4 class="text-danger">Later</h4>
						<p class="text-muted"><?php echo $later_hour ?>:00 - <?php echo $later_hour + 1 ?>:00</p>
						<p class="mt-2 font-weight-bold"><?php echo $later_dj_span ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
setInterval(updateStats, 5000);
setTimeout(updateStats, 500);
function updateStats(url) {
  return new Promise((resolve, reject) => {
		$("#listeners").load('./scripts/stats.php?specific=listeners', function() {
		  resolve("Success!");
		});
  });
}
</script>
