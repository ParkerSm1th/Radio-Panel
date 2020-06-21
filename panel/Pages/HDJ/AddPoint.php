<?php
  $perm = 2;
  $media = 0;
  $radio = 0;
  $dev = 0;
  $title = "Add Point";
  include('../../includes/header.php');
  include('../../includes/config.php');
  if (isset($_GET['id'])) {
    $specificID = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $specificID);
    $stmt->execute();
    $spec = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($spec['permRole'] == 1) {
      $color = 'dstaff-text';
    }
    if ($spec['permRole'] == 2) {
      $color = 'sstaff-text';
    }
    if ($spec['permRole'] == 3) {
      $color = 'manager-text';
    }
    if ($spec['permRole'] == 4) {
      $color = 'admin-text';
    }

    if ($spec['permRole'] == 5) {
      $color = 'executive-text';
    }

    if ($spec['permRole'] == 6) {
      $color = 'owner-text';
    }
    $user = "<span class='" . $color . " userLink' onclick='loadProfile(" . $spec['id'] . ")'>" . $spec['username'] . "</span>";
  }
  $id = $_SESSION['loggedIn']['id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($spec['permRole'] >= $row['permRole'] && $_SESSION['loggedIn']['id'] !== '1') {
    ?>
      <script>
        urlRoute.loadPage('Staff.Dashboard');
      </script>
    <?php
    exit();
  }
 ?>
 <div class="card">
   <div class="card-body">
      <?php
      if ($spec['username'] !== null) {
        ?>
          <h4 class="card-title">Giving <?php echo $user ?> a point</h4>
        <?php
      } else {
        ?>
        <?php
      }?>
     <form class="forms-sample" id='addPoint' action="#">
       <div class="form-group" id='errorFieldOut2' style='display: none;'>
         <span class="btn btn-danger submit-btn btn-block" id='errorField2'></span>
       </div>
       <div class="form-group">
         <?php
            if ($spec['username'] !== null) {
              ?>
                <input class="form-control" readonly="" value="<?php echo $spec['id'] ?>" name="user" id="user" style="display: none;"></input>
              <?php
            } else {
              ?>
                <label for="value">User</label>
                <div class="custom-select">
                  <select class="form-control" name="user" id="user">
                    <option value="0" disabled selected>Please select a user</option>
                    <?php
                    if ($_SESSION['loggedIn']['id'] == 1) {
                      $stmt = $conn->prepare("SELECT * FROM users ORDER BY username");
                      $stmt->bindParam(':role', $_SESSION['loggedIn']['permRole']);
                    } else {
                      $stmt = $conn->prepare("SELECT * FROM users WHERE permRole < :role ORDER BY username");
                      $stmt->bindParam(':role', $_SESSION['loggedIn']['permRole']);
                    }

                    $stmt->execute();
                      foreach($stmt as $row) {
                      if ($row['avatarURL'] !== null && $row['avatarURL'] !== '') {
                        $avatar = "https://staff.keyfm.net/profilePictures/" . $row['avatarURL'];
                      } else {
                        $avatar = "https://staff.keyfm.net/images/default.png";
                      }
                      ?>
                        <option value="<?php echo $row['id'] ?>" data-ico="<?php echo $avatar?>"><?php echo $row['username']?></option>
                    <?php } ?>
                  </select>
                </div>
              <?php
            }
         ?>
       </div>
       <div class="form-group">
         <label for="value">Point Type</label>
         <div class="custom-select">
            <select class="form-control" name="type" id="type">
              <option disabled selected>Please select a point type</option>
              <?php
              $stmt = $conn->prepare("SELECT * FROM point_types ORDER BY type");
              $stmt->execute();
                foreach($stmt as $row) {
                if ($row['type'] == 0) {
                  $symbol = '+';
                } else if ($row['type'] == 1) {
                  $symbol = '-';
                } else if ($row['type'] == 2) {
                  $symbol = '';
                }
                ?>
                  <option value="<?php echo $row['id'] ?>">[<?php echo $symbol . $row['points']?>] <?php echo $row['title'] ?></option>
              <?php } ?>
            </select>
          </div>
       </div>
       <div class="form-group">
         <label for="region">Reason</label>
         <textarea type="text" rows="3" name="reason" id="reason" class="form-control" placeholder="Please enter the specific reason for this point"></textarea>
       </div>
       <div class="form-group" style="text-align: center; padding-top: 20px;">
         <button class="btn btn-success mr-2" id='submit'>Add Point</button>
       </div>

     </form>
   </div>
 </div>
<script>
var form2 = $('#addPoint');
$(form2).submit(function(event) {
    var error = false;
    var errorMessage = '';
    event.preventDefault();
    var formData = $(form2).serialize();
    if ($("#user").val() == null || $("#user").val() == "0" || $("#type").val() == null || $("#type").val() == "0" || $("#reason").val() == null || $("#reason").val() == "") {
      error = true;
      errorMessage = "Please fill out all of the fields";
    }
    if (error) {
      $('#errorFieldOut2').fadeIn();
      $('#errorField2').html(errorMessage);
      return true;
    }

    $.ajax({
        type: 'POST',
        url: 'scripts/addPoint.php',
        data: formData
    }).done(function(response) {
      console.log(response);
      if (response == 'added') {
        $('#errorFieldOut2').fadeIn();
        $('#errorField2').removeClass('btn-danger');
        $('#errorField2').addClass('btn-success');
        $('#errorField2').html('Success! Point Addded');
      } else {
        $('#errorField2').addClass('btn-danger');
        $('#errorField2').removeClass('btn-success');
        $('#errorFieldOut2').fadeIn();
        $('#errorField2').html('Unknown error occured..');
      }
    }).fail(function (response) {
        $('#errorFieldOut2').fadeIn();
        $('#errorField2').html('Unknown error occured.');
    });
  });

</script>
