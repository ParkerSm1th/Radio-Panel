<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Applications";
include('../../includes/header.php');
include('../../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}
 ?>
     <div class="row">
       <?php
       $stmt = $conn->prepare("SELECT * FROM applications WHERE status = 0 OR status = 1 ORDER BY status, id DESC");
       $stmt->execute();
       $count = $stmt->rowCount();
       if ($count !== 0) {
         foreach($stmt as $row) {
           if ($row['status'] == 0) {
             $status = "Not yet contacted";
           } else if ($row['status'] == 1) {
             $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
             $stmt->bindParam(":id", $row['assigned']);
             $stmt->execute();
             $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
             if ($userDetails['permRole'] == 1) {
               $color = 'dstaff-text';
             }
             if ($userDetails['permRole'] == 2) {
               $color = 'sstaff-text';
             }
             if ($userDetails['permRole'] == 3) {
               $color = 'manager-text';
             }
             if ($userDetails['permRole'] == 4) {
               $color = 'admin-text';
             }

             if ($userDetails['permRole'] == 5) {
               $color = 'executive-text';
             }

             if ($userDetails['permRole'] == 6) {
               $color = 'owner-text';
             }
             $status = "Contacted by <span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";
           }

           if ($row['role'] == 0) {
             $role = "<i class='fa fa-microphone-alt'></i>";
           } else if ($row['role'] == 1) {
             $role = "<i class='fa fa-newspaper'></i>";
           } else if ($row['role'] == 2) {
             $role = "<i class='far fa-share-alt'></i>";
           }
          ?>
          <div class="col-md-4 col-sm-12">
            <div class="application">
              <div class="app-header bg-<?php echo $row['region'] ?>">
                <h1 class="region"><?php echo $row['region'] ?></h1>
                <p class="name"><?php echo $role ?> <?php echo $row['name'] ?></p>
                <p class="discord"><?php echo $row['discord'] ?></p>
              </div>
              <div class="app-body">
                <div class="buttons">
                  <button data-id="<?php echo $row['id'] ?>" class="viewButton btn btn-info">View / Manage</button>
                </div>
                <div class="status">
                  <h1><?php echo $status ?></h1>
                </div>
              </div>
            </div>
          </div>
        <?php }
      } else {
        ?>
        <h2 style="margin: auto; color: rgba(152, 152, 152, 0.49); padding-top: 30px;">No new applications, you guys got them all 😊</h2>
        <?php
      }?>


     </div>

     <h3 class="text-white">Closed Applications</h3>
     <div class="row">
       <?php
       $stmt = $conn->prepare("SELECT * FROM applications WHERE status = 2 OR status = 3 ORDER BY id DESC");
       $stmt->execute();
       $count = $stmt->rowCount();
       if ($count !== 0) {
         foreach($stmt as $row) {
           $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
           $stmt->bindParam(":id", $row['assigned']);
           $stmt->execute();
           $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
           if ($userDetails['permRole'] == 1) {
             $color = 'dstaff-text';
           }
           if ($userDetails['permRole'] == 2) {
             $color = 'sstaff-text';
           }
           if ($userDetails['permRole'] == 3) {
             $color = 'manager-text';
           }
           if ($userDetails['permRole'] == 4) {
             $color = 'admin-text';
           }

           if ($userDetails['permRole'] == 5) {
             $color = 'executive-text';
           }

           if ($userDetails['permRole'] == 6) {
             $color = 'owner-text';
           }
           if ($row['status'] == 3) {
             $status = "Denied by <span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";
           } else if ($row['status'] == 2) {
             $status = "Hired by <span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";
           }

           if ($row['role'] == 0) {
             $role = "<i class='fa fa-microphone-alt'></i>";
           } else if ($row['role'] == 1) {
             $role = "<i class='fa fa-newspaper'></i>";
           } else if ($row['role'] == 2) {
             $role = "<i class='far fa-share-alt'></i>";
           }

          ?>
          <div class="col-md-4 col-sm-12">
            <div class="application">
              <div class="app-header bg-<?php echo $row['region'] ?>">
                <h1 class="region"><?php echo $row['region'] ?></h1>
                <p class="name"><?php echo $role ?> <?php echo $row['name'] ?></p>
                <p class="discord"><?php echo $row['discord'] ?></p>
              </div>
              <div class="app-body">
                <div class="buttons">
                  <button data-id="<?php echo $row['id'] ?>" class="viewButton btn btn-info">View</button>
                  <button data-id="<?php echo $row['id'] ?>" class="reopenButton btn btn-info">Reopen</button>
                </div>
                <div class="status">
                  <h1><?php echo $status ?></h1>
                </div>
              </div>
            </div>
          </div>
        <?php }
      } else {
        ?>
          <h2 style="margin: auto; color: rgba(152, 152, 152, 0.49); padding-top: 30px;">No closed applications, you guys got them all 😊</h2>
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
