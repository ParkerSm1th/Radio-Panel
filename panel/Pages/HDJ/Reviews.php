<?php
$perm = 2;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Reviews Due";
include('../../includes/header.php');
include('../../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}
 ?>
 <div class="card">
  <div class="card-head">
      <h1>Assigned Reviews</h1>
  </div>
   <div class="card-body">
     <div class="row">
       <?php
       $stmt = $conn->prepare("SELECT * FROM review_assignments WHERE user = :id AND completed = 0 ORDER BY id DESC");
       $stmt->bindParam(":id", $_SESSION['loggedIn']['id']);
       $stmt->execute();
       $count = $stmt->rowCount();
       if ($count > 0) {
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
          $user = "<span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";

          ?>
          <div class="col-md-4 col-sm-12">
            <div class="ra">
              <div class="ra-header">
                <p class="name"><?php echo $user ?></p>
              </div>
              <div class="ra-body">
                <div class="buttons">
                  <button data-id="<?php echo $row['assigned'] ?>" class="doButton btn btn-info">Write Review</button>
                </div>
              </div>
            </div>
          </div>
        <?php }
      } else {
        ?>
        <h2 style="margin: auto; color: #ffffffe8; padding-top: 20px; padding-bottom: 20px;">No assigned reviews! You did them all! 😊</h2>
        <?php
      }?>


     </div>
    </div>
    </div>
  <div class="card m-t-20">
  <div class="card-head">
      <h1>Completed Reviews</h1>
  </div>
   <div class="card-body">
     <div class="row">
     <?php
       $stmt = $conn->prepare("SELECT * FROM review_assignments WHERE user = :id AND completed = 1 ORDER BY id DESC");
       $stmt->bindParam(":id", $_SESSION['loggedIn']['id']);
       $stmt->execute();
       $count = $stmt->rowCount();
       if ($count > 0) {
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
          $user = "<span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";

          ?>
          <div class="col-md-4 col-sm-12">
            <div class="ra">
              <div class="ra-header">
                <p class="name"><?php echo $user ?></p>
              </div>
              <div class="ra-body">
                <div class="buttons">
                  <button data-id="<?php echo $row['assigned'] ?>" class="viewButton btn btn-info">View Review</button>
                </div>
              </div>
            </div>
          </div>
        <?php }
      } else {
        ?>
        <h2 style="margin: auto; color: #ffffffe8; padding-top: 20px; padding-bottom: 20px;">You haven't done any reviews yet this week. 🤔</h2>
        <?php
      }?>


     </div>
    </div>
    </div>
 <script>
  $(".viewButton").on("click", function () {
    urlRoute.loadPage("HDJ.ViewReview?id=" + $(this).attr('data-id'));
  });
  $(".doButton").on("click", function() {
    urlRoute.loadPage("HDJ.NewReview?id=" + $(this).attr('data-id'));
  });
 </script>
