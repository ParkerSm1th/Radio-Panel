<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Perm Shows";
include('../../includes/header.php');
include('../../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}
 ?>
     <div class="row">
       <?php
       $stmt = $conn->prepare("SELECT * FROM perm_shows ORDER BY id DESC");
       $stmt->execute();
       $count = $stmt->rowCount();
       if ($count !== 0) {
         foreach($stmt as $row) {
            $stmt = $conn->prepare("SELECT * FROM timetable WHERE id = :id");
            $stmt->bindParam(":id", $row['time']);
            $stmt->execute();
            $timeDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            $day = null;
            switch($timeDetails['day']) {
              case 0:
                $day = "Monday";
                break;
              case 1:
                $day = "Tuesday";
                break;
              case 2:
                $day = "Wednesday";
                break;
              case 3:
                $day = "Thursday";
                break;
              case 4:
                $day = "Friday";
                break;
              case 5:
                $day = "Saturday";
                break;
              case 6:
                $day = "Sunday";
                break;
            }
          ?>
          <div class="col-md-4 col-sm-12">
            <div class="application">
              <div class="app-header bg-na">
                <h1 class="region"></h1>
                <p class="name"><?php echo $row['name']?></p>
                <p class="discord"><?php echo $day?> @ <?php echo $timeDetails['timestart']?>:00</p>
              </div>
              <div class="app-body">
                <div class="buttons">
                  <button data-id="<?php echo $row['id'] ?>" class="viewButton btn btn-info">View / Manage</button>
                </div>
                <div class="status">
                  <h1></h1>
                </div>
              </div>
            </div>
          </div>
        <?php }
      } else {
        ?>
        <h2 style="margin: auto; color: rgba(152, 152, 152, 0.49); padding-top: 30px;">No perm shows :)</h2>
        <?php
      }?>


     </div>


 <script>
 $(".viewButton").on("click", function () {
   urlRoute.loadPage("Manager.ViewApplication?id=" + $(this).attr('data-id'));
 });
 $(".reopenButton").on("click", function() {
   $.ajax({
       type: 'GET',
       url: `scripts/editApplication.php?id=${$(this).attr('data-id')}&action=reopen`
   }).done(function(response) {
     if (response == "done") {
       newSuccess("You have reopened this application");
       urlRoute.loadPage("Manager.Applications");
     } else {
       newError("An unknown error occured.");
     }
   });
 });
 </script>
