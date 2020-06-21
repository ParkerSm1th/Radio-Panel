<?php
  $perm = 2;
  $media = 0;
  $radio = 0;
  $dev = 0;
  $debug = true;
  $title = "View Review";
  include('../../includes/header.php');
  include('../../includes/config.php');
?>
<div class="card" style="margin-bottom: 20px;">
  <div class="card-body">
    <?php
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(":id", $_GET['id']);
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
    <h4 class="card-title">Non-Published Review for <?php echo $user?></h4>
    <div class="card-actions">
      <a href="HDJ.Reviews" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">Back</button>
      </a>
      <a href="HDJ.EditReview?id=<?php echo $_GET['id']?>" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">Edit Review</button>
      </a>
    </div>
    <?php
      $stmt = $conn->prepare("SELECT * FROM reviews WHERE user = :id AND published = 0 ORDER BY id DESC LIMIT 1");
      $stmt->bindParam(':id', $_GET['id']);
      $stmt->execute();
      $count = $stmt->rowCount();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($count == 0) {
        ?>
          <div class="text-center">
          <h2 style="margin: auto;
          color: #ffffffe8;
          padding-top: 30px;
          text-align: center;">That user does not have a review written this week yet! 🤔</h2>
          </div>
        <?php
      } else {
        $rType = $row['type'];
        $content = $row['content'];
        $impro = $row['impro'];
        if ($rType == 0) {
          $type = "Grey";
          $class = "grey";
          $stars = '';
          $impro = null;
        }
        if ($rType == 1) {
          $type = "Red";
          $class = "red";
          $stars = '<i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fal fa-star"></i>
          <i class="fal fa-star"></i>
          <i class="fal fa-star"></i>';
        }
        if ($rType == 2) {
          $type = "Blue";
          $class = "blue";
          $stars = '<i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fal fa-star"></i>
          <i class="fal fa-star"></i>';
        }
        if ($rType == 3) {
          $type = "Green";
          $class = "green";
          $stars = '<i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fal fa-star"></i>';
        }
        if ($rType == 4) {
          $type = "Gold";
          $class = "gold";
          $stars = '<i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>';
        }
        ?>
          <div class="review">
            <div class="review-header <?php echo $class ?>Review">
              <div class="type">
                <h1><?php echo $type ?> Review</h1>
              </div>
              <div class="date">
                <p><?php echo $row['times'] ?></p>
              </div>
              <div class="rating">
                <?php echo $stars ?>
              </div>
            </div>
            <div class="content">
              <div class="sec">Overall</div>
              <p><?php echo $content ?></p>
            </div>
            <?php
              if ($impro !== null) {
                ?>
                  <div class="impro">
                    <div class="sec">Improvement</div>
                    <p><?php echo $impro?></p>
                  </div>
                <?php
              }
            ?>
          </div>
        <?php
      }
    ?>
  </div>
</div>
