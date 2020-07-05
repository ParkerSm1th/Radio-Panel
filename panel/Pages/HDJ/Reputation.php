<?php
  $perm = 1;
  $media = 0;
  $radio = 0;
  $dev = 0;
  $title = "Reputation";
  include('../../includes/header.php');
  include('../../includes/config.php');
  $id = $_SESSION['loggedIn']['id'];
  $stmt = $conn->prepare("SELECT * FROM points WHERE user = :id");
  $stmt->bindParam(':id', $id);
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
 <?php
 $cssMath = (($total * 10) / 2);
 if ($total !== 0) {
   $left = 50 + $cssMath;
   $right = 50 - $cssMath;
 } else {
   $left = 50;
   $right = 50;
 }
  ?>
 <style>
 .pointsLine {
    width: 90%;
    height: 10px;
    border-radius: 5px;
    background-color: #373f637a;
    position: relative;
    margin: auto;
    margin-top: 30px;
  }
  .pointsLineCoR {
     width: <?php echo $right?>%;
     height: 10px;
     border-radius: 5px;
     position: relative;
     margin: auto;
     margin-top: -10px;
     margin-right: 0px;
   }
   .pointsLineCoL {
      width: <?php echo $left?>%;
      height: 10px;
      border-radius: 5px;
      background-color: #373f637a;
      position: relative;
      margin: auto;
      margin-top: 30px;
      margin-left: 0px;
    }
  .pointsValue {
    display: inline-block;
    vertical-align: middle;
    width: 36px;
    height: 36px;
    color: #fff;
    background: #444444fa;
    border-radius: 50%;
    position: absolute;
    top: -12px;
    left: calc(50%);
    transform: translateX(-50%);
    margin-left: <?php echo (($total * 10) / 2)?>%;
  }
  .pointsValue p {
    font-size: 18px;
    padding-top: 3px;
    text-align: center;
  }
  .markers .high {
    position: absolute;
    right: -60px;
    top: -15px;
    font-size: 21px;
    padding: 5px;
    color: #ffffff;
    text-align: center;
    border-radius: 15px;
  }
  .markers .low {
    position: absolute;
    left: -60px;
    top: -15px;
    font-size: 21px;
    padding: 5px;
    color: #ffffff;
    text-align: center;
    border-radius: 15px;
  }
  .positive {
    background-color: #1a8002 !important;
  }
  .negative {
    background-color: #f50808 !important;
  }
  .negativeLine {
    background-color: #f50808c9 !important;
  }
  .positiveLine {
    background-color: #1a8002c9 !important;
  }
  h2.card-title {
      color: #fff;
      font-size: 20px !important;
  }
  .pointsHLine {
    width: 10px;
    background: #1c3253;
    border-radius: 5px;
    display: inline-block;
    margin-bottom: 5%;
  }

  .points {
    display: inline-block;
    margin-bottom: 0px;
  }
  .points li {
    margin-bottom: 15px;
  }
  .points li:last-child {
    margin-bottom: 0px;
  }
  .pointValue {
    color: #fff;
    position: absolute;
    font-size: 20px;
    height: 33px;
    width: 33px;
    text-align: center;
    border-radius: 100%;
    left: 18px;
    background: #504d4de0;
  }
  .pointValue p {
    font-size: 20px;
    margin-top: -2px;
  }
  .point {
    background: #1d3353;
    padding: 10px;
    border-radius: 10px;
    position: relative;
    min-width: 520px;
    margin-left: 10px;
  }
  .point h1 {
    font-size: 23px;
    color: #fff;
    font-weight: 500;
    padding-right: 150px;
  }
  .point p {
    color: #fff;
    padding-bottom: 0px !important;
    margin-bottom: 0px !important;
  }
  .point p.date {
    color: #ffffffa8;
    position: absolute;
    top: 5px;
    right: 10px;
  }
  .pointRow {
    display: flex; /* equal height of the children */
  }
 </style>
<div class="card">
  <div class="card-body">
    <h1 class="card-title">Your Staff Points</h1>
    <h2 class="card-title text-center">Overall Reputation</h2>
    <div class="pointsLine">
      <div class="pointsLineCoL positiveLine"></div>
      <div class="pointsLineCoR negativeLine"></div>
      <div class="markers">
        <p class="low">-10</p>
        <p class="high">10</p>
      </div>
      <?php
        if ($total > 0) {
          $class = "positive";
          $sign = "+";
        } else if ($total < 0) {
          $class = "negative";
        }
      ?>
      <div class="pointsValue <?php echo $class ?>">
        <p><?php echo $sign . $total?></p>
      </div>
    </div>
    <h2 class="card-title text-center" style="margin-top: 30px;">Point History</h2>
    <div class="pointRow">
      <div class="pointsHLine pointCol"></div>
      <ul class="points pointCol">
        <?php
          $stmt = $conn->prepare("SELECT * FROM points WHERE user = :id ORDER BY id DESC");
          $stmt->bindParam(':id', $id);
          $stmt->execute();
          $count = $stmt->rowCount();
          foreach($stmt as $row) {
            $value = $row['value'];
            if ($row['type'] == 0) {
              $class = "positive";
              $value = "+" . $row['value'];
            } else if ($row['type'] == 1) {
              $class = "negative";
              $value = "-" . $row['value'];
            } else {
              $class = "";
            }
            ?>
              <li>
                <div class="pointValue <?php echo $class ?>"><p><?php echo $value ?></p></div>
                <div class="point">
                  <h1><?php echo $row['title'] ?></h1>
                  <p class="date"><?php echo $row['times'] ?></p>
                  <p><?php echo $row['message'] ?></p>
                </div>
              </li>
            <?php
          }
          if ($count == 0) {
            ?>
              <style>
                .pointsHLine {
                  margin-bottom: 0px !important;
                }
              </style>
            <?php
          }
         ?>
         <li>
           <div class="pointValue"><p>0</p></div>
           <div class="point">
             <h1>Joined Team</h1>
             <?php
             $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
             $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
             $stmt->execute();
             $row = $stmt->fetch(PDO::FETCH_ASSOC);
              ?>
             <p class="date"><?php echo $row['hired'] ?></p>
             <p>You joined our wonderful staff team!</p>
           </div>
         </li>
      </ul>
    </div>

  </div>

</div>
