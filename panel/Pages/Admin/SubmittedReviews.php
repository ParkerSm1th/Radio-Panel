<?php
  $perm = 4;
  $media = 0;
  $radio = 0;
  $dev = 0;
  $title = "Submitted Reviews";
  include('../../includes/header.php');
  include('../../includes/config.php');
?>
<style>
  .review {
    background: #22457761;
    max-width: 500px;
    border-radius: 5px;
    margin: auto;
    padding-bottom: 10px;
  }
  .review-header {
    border-radius: 5px 5px 0 0;
    height: 60px;
    position: relative;
  }
  .review-header .type {
    position: absolute;
    top: 50%;
    left: 20px;
    transform: translateY(-50%);
  }
  .review-header .type h1 {
    font-size: 29px;
    color: #fff;
    margin-bottom: 0px;
  }
  .review-header .date {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
  }
  .review-header .date p {
    color: #ffffffb5;
    font-size: 18px;
    margin-bottom: 00px;
  }
  .review-header .rating {
    position: absolute;
    top: 50%;
    right: 110px;
    font-size: 20px;
    transform: translateY(-50%);
    color: #fff;
  }
  .review-header .rating .fas {
    color: #fff900;
  }
  .content {
    color: #fff;
    margin: 10px;
  }
  .content p {
    font-size: 15px;
    padding: 0 5px;
  }
  .content .sec {
    background: #006f9c;
    padding: 4px;
    text-align: center;
    border-radius: 2px;
    margin-bottom: 10px;
    font-size: 18px;
  }
  .impro { 
    color: #fff;
    margin: 10px;
  }
  .impro p {
    font-size: 15px;
    padding: 0 5px;
  }
  .impro .sec {
    background: #0a9c00;
    padding: 4px;
    text-align: center;
    border-radius: 2px;
    margin-bottom: 10px;
    font-size: 18px;
  }
  .goldReview {
    background: #948303;
  }
  .greenReview {
    background: #059c00;
  }
  .blueReview {
    background: #00a6c1;
  }
  .redReview {
    background: #dc192b;
  }
  .greyReview {
    background: #a7a7a78a;
  }
</style>
    <?php
      $stmt = $conn->prepare("SELECT * FROM reviews WHERE published = 0 ORDER BY id");
      $stmt->execute();
      $count = $stmt->rowCount();
      if ($count == 0) {
        ?>
          <div class="text-center">
          <h2 style="margin: auto;
          color: #ffffffe8;
          padding-top: 30px;
          text-align: center;">There are currently no submitted reviews 🤔</h2>
          </div>
        <?php
      } else {
        foreach($stmt as $row) {
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
          <div class="review" style="margin-bottom: 20px;">
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
            <div class="content">
              <?php
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
                  $stmt->bindParam(":id", $row['admin']);
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
                  $writtenBy = "<span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";
                ?>
                <?php
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
                  $stmt->bindParam(":id", $row['user']);
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
                  $writtenFor = "<span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";
                ?>
                <div style="background: #00000036;" class="sec">For <?php echo $writtenFor?> By <?php echo $writtenBy ?></div>
            </div>
            
          </div>
        <?php
        }
      }
    ?>