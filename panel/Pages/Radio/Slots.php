<?php
$perm = 1;
$media = 0;
$radio = 1;
$dev = 0;
$title = "Your Slots";
include('../../includes/header.php');
include('../../includes/config.php');
$id = $_SESSION['loggedIn']['id'];
$first = null;
$firstCount = null;
$second = null;
$secondCount = null;
$third = null;
$thirdCount = null;
$stmt = $conn->prepare("SELECT * FROM users ORDER BY id");
$stmt->execute();
foreach($stmt as $row) {
  $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id");
  $stmt->bindParam(":id", $row['id']);
  $stmt->execute();
  $count = $stmt->rowCount();
  if ($first == null) {
    $third = $second;
    $thirdCount = $secondCount;
    $second = $first;
    $secondCount = $firstCount;
    $first = $row['id'];
    $firstCount = $count;
  } else {
    if ($count > $firstCount) {
      $third = $second;
      $thirdCount = $secondCount;
      $second = $first;
      $secondCount = $firstCount;
      $first = $row['id'];
      $firstCount = $count;
    } else {
      if ($second == null) {
        $third = $second;
        $thirdCount = $secondCount;
        $second = $row['id'];
        $secondCount = $count;
      } else {
        if ($count > $secondCount) {
          $third = $second;
          $thirdCount = $secondCount;
          $second = $row['id'];
          $secondCount = $count;
        } else {
          if ($third == null) {
            $third = $row['id'];
            $thirdCount = $count;
          } else {
            if ($count > $thirdCount) {
              $third = $row['id'];
              $thirdCount = $count;
            }
          }
        }
      }
    }
  }
}
 ?>
 <style>
  .card-title.big{
    text-align: center;
    font-size: 35px !important;
    font-weight: 500;
  }
  .leaderboard {
    margin: auto;
    margin-top: 50px;
    text-align: center;
    display: flex;
    align-content: center;
    justify-content: center;
    align-items: center;
  }
  .rank {
    display: inline-block;
    box-shadow: inset 0 0 195px #ffffff4d;
    width: 30%;
    align-self: flex-end;
    margin: 0 20px;
    display: flex;
    align-items: center;
    position: relative;
    border-radius: 5px;
  }
  .rank-content {
    margin: auto;
    z-index: 1;
  }
  .rank h1 {
    padding-top: 10px;
    color: #fff;
    margin-bottom: 0px;
  }
  .rank h1 img {
    border-radius: 100%;
    height: 80px;
    width: 80px;
  }
  .rank h2 {
    color: #fff;
    font-size: 40px;
  }
  .num {
    position: absolute;
    left: 50%;
    color: #d2d2d236;
    transform: translateX(-50%);
    z-index: 0;
  }
  .first {
    height: 250px;
  }
  .first .num {
    font-size: 300px;
  }
  .second {
    height: 200px;
  }
  .second .num {
    font-size: 240px;
  }
  .third {
    height: 150px;
  }
  .third .num {
    font-size: 190px;
  }
  .blurBG {
      width: 100%;
      height: 100%;
      position: absolute;
      overflow: hidden;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 1;
      opacity: .5;
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
  }
  .o8 {
    opacity: 0.8 !important;
  }
  .blurBG.s>img {
    filter: blur(30px) saturate(230%);
  }

  .blurBG img {
      width: 100%;
      height: 100%;
      position: absolute;
      filter: blur(30px);
      left: 0;
      top: 0;
  }
  .rank.you {
    margin-top: 40px;
    height: 210px;
    width: auto;
  }
  .rank.you .num {
    font-size: 200px;
  }
  .rank.you h2 {
    font-size: 60px;
    text-align: center;
  }
  .smallF {
    display: none;
  }
  @media screen and (max-width: 1106px) {
    .leaderboard {
      display: block;
    }
    .rank {
      width: auto;
      margin: 20px auto;
    }
    .first, .second, .third {
      height: 175px !important;
    }
    .num {
      font-size: 100px !important;
      color: #d2d2d2b3;
    }
    .bigF {
      display: none;
    }
    .smallF {
      display: flex;
    }
  }

 </style>
    <div class="leaderboard">
      <div class="smallF first rank">
        <div class="num">1</div>
        <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
          $stmt->bindParam(":id", $first);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="blurBG s o8"><img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"></div>
         <div class="rank-content">
           <h1><img draggable="false" src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $row['id'] ?>)"><?php echo $row['username'] ?></span></h1>
           <h2><?php echo $firstCount ?></h2>
         </div>
      </div>
      <div class="second rank">
        <div class="num">2</div>
        <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
          $stmt->bindParam(":id", $second);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="blurBG s o8"><img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"></div>
        <div class="rank-content">
          <h1><img draggable="false" src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $row['id'] ?>)"><?php echo $row['username'] ?></span></h1>
          <h2><?php echo $secondCount ?></h2>
        </div>
      </div>
      <div class="bigF first rank">
        <div class="num">1</div>
        <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
          $stmt->bindParam(":id", $first);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="blurBG s o8"><img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"></div>
         <div class="rank-content">
           <h1><img draggable="false" src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $row['id'] ?>)"><?php echo $row['username'] ?></span></h1>
           <h2><?php echo $firstCount ?></h2>
         </div>
      </div>
      <div class="third rank">
        <div class="num">3</div>
        <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
          $stmt->bindParam(":id", $third);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="blurBG s o8"><img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"></div>
         <div class="rank-content">
           <h1><img draggable="false" src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $row['id'] ?>)"><?php echo $row['username'] ?></span></h1>
           <h2><?php echo $thirdCount ?></h2>
         </div>
      </div>
    </div>
    <div class="rank you">
      <div class="num">YOU</div>
      <?php
        $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id");
        $stmt->bindParam(":id", $_SESSION['loggedIn']['id']);
        $stmt->execute();
        $count = $stmt->rowCount();
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(":id", $_SESSION['loggedIn']['id']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       ?>
       <div class="blurBG s o8"><img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"></div>
      <div class="rank-content">
        <h1><img draggable="false" src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/default.png'" alt="image"> <span class="userLink" onclick="loadProfile(<?php echo $row['id'] ?>)"><?php echo $row['username'] ?></span></h1>
        <h2><?php echo $count ?></h2>
      </div>
<script>

</script>
