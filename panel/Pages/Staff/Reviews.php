<?php
  $perm = 1;
  $media = 0;
  $radio = 0;
  $title = "Your Reviews";
  include('../../includes/header.php');
  include('../../includes/config.php');
?>
<div class="card" style="margin-bottom: 20px;">
  <div class="card-head">
    <h1>Most Recent Review</h1>
  </div>
  <div class="card-body">
    <?php
      $stmt = $conn->prepare("SELECT * FROM reviews WHERE user = :id AND published = 1 ORDER BY id DESC LIMIT 1");
      $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
      $stmt->execute();
      $count = $stmt->rowCount();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($count == 0) {
        ?>
          <div class="text-center">
          <h2 style="margin: auto;
          color: #ffffffe8;
          padding-top: 30px;
          text-align: center;">You do not have a published review yet! 🤔</h2>
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
<div class="card">
<div class="card-head">
    <h1>Past Reviews</h1>
  </div>
  <div class="card-body">
    <?php
      $stmt = $conn->prepare("SELECT * FROM reviews WHERE user = :id AND published = 1 ORDER BY id DESC LIMIT 500 OFFSET 1");
      $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
      $stmt->execute();
      $count = $stmt->rowCount();
      if ($count == 0) {
        ?>
          <div class="text-center">
          <h2 style="margin: auto;
          color: #ffffffe8;
          padding-top: 30px;
          text-align: center;">You do not have any past reviews yet! 🤔</h2>
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
          </div>
        <?php
        }
      }
    ?>
  </div>
</div>