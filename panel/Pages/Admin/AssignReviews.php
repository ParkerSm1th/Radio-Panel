<?php
$perm = 4;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Assign Reviews";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
 <style>
   p.ra-sub {
    color: #ffffffc2;
    text-align: center;
    margin: 10px;
    font-size: 14px;
   }
 </style>
    <?php
      $stmt = $conn->prepare("SELECT * FROM review_assignments");
      $stmt->execute();
      $gtotal = $stmt->rowCount();
      $stmt = $conn->prepare("SELECT * FROM review_assignments WHERE completed = 1");
      $stmt->execute();
      $gfinished = $stmt->rowCount();
    ?>
    <div class="alert alert-warning text-center" role="alert">
       This page loads a LOT of data so when you go to this page and leave it there may be some slight lag but there shouldn't be while you are on the page, thanks for understanding :) -<span class="owner-text userLink" onclick="loadProfile(1)">Parker</span> <3
     </div>
     <div class="row">
       <?php
       $stmt = $conn->prepare("SELECT * FROM users WHERE permRole >= 2 ORDER BY permRole");
       $stmt->execute();
       $count = $stmt->rowCount();
      foreach($stmt as $row) {
          $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
          $stmt->bindParam(":id", $row['id']);
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
                <?php
                  $stmt = $conn->prepare("SELECT * FROM review_assignments WHERE user = :id");
                  $stmt->bindParam(":id", $userDetails['id']);
                  $stmt->execute();
                  $total = $stmt->rowCount();
                  $stmt = $conn->prepare("SELECT * FROM review_assignments WHERE user = :id AND completed = 1");
                  $stmt->bindParam(":id", $userDetails['id']);
                  $stmt->execute();
                  $finished = $stmt->rowCount();
                ?>
                <p class="name"><?php echo $user ?> <span style="color: #ccc;font-size: 14px;">(<?php echo $finished ?>/<?php echo $total?>)</span></p>
              </div>
              <div class="ra-body ra-body-auto">
                <p class="ra-sub">Please assign reviews below</p>
                <input id="autoc<?php echo $userDetails['id']?>" data-id="<?php echo $userDetails['id']?>" class="form-control autocUser" type="text" name="test" placeholder="Loading.."></input>
              </div>
            </div>
          </div>
          <script>
            $.ajax({
                type: 'POST',
                url: `scripts/manageAssignments.php?action=get&user=<?php echo $userDetails['id']?>`
            }).done(function(response) {
              var prePop = JSON.parse(response);
              $("#autoc<?php echo $userDetails['id']?>").tokenInput("./scripts/findReviewUser.php", {
                theme: "mac",
                preventDuplicates: true,
                hintText: "Search for a user to assign",
                searchingText: '<i class="fas fa-circle-notch fa-spin" style="color: #fff; font-size: 15px; margin: auto; text-align: center; margin-left: 85px;"></i>',
                prePopulate: prePop,
                onAdd: function (item) {
                    var assigned = item.id;
                    var admin = $(this).attr("data-id");
                    $.ajax({
                        type: 'POST',
                        url: `scripts/manageAssignments.php?action=add&user=${admin}&assigned=${assigned}`
                    }).done(function(response) {

                    });
                },
                onDelete: function (item) {
                    var assigned = item.id;
                    var admin = $(this).attr("data-id");
                    $.ajax({
                        type: 'POST',
                        url: `scripts/manageAssignments.php?action=del&user=${admin}&assigned=${assigned}`
                    }).done(function(response) {

                    });
                },
              });
            });

          </script>
        <?php }?>


     </div>


<script>

</script>
