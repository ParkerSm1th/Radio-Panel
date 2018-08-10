<?php
session_start();
if ($_SESSION['loggedIn'] == null) {
  header("Location: ../../index.php");
  exit();
}
include('../includes/config.php');
if ($_GET['id'] == null) {
  echo "error";
} else {
  $id = $_GET['id'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row['username'] == null) {
    echo "Invalid User";
    return true;
  }
  if ($row['region'] == "Global") {
    $region = '<i class="fas fa-globe-americas"></i>';
  } else {
    $region = $row['region'];
  }
  if ($row['discord'] == null) {
    $discord = 'None Set.';
  } else {
    $discord = $row['discord'];
  }
  ?>
    <div class="profile">
      <div class="profileImage" style='padding-bottom: 10px;'>
        <img src="<?php echo $row['avatarURL'] ?>" style='border-radius: 100%' onerror="this.src='../images/Logo.png'">
      </div>
      <?php
        if ($row['inactive'] == "true") {
          ?>
            <h1>Pending - <?php echo $row['username'] ?></h1>
          <?php
        } else {
          ?>
            <h1><?php echo $row['username'] ?></h1>
          <?php
        }
       ?>
      <h2><?php
      if ($row['radio'] == '1') {
        ?>
        <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
        <?php
      }
      if ($row['media'] == '1') {
        ?>
        <span class="cTooltip"><i class='fa fa-newspaper'></i><b title="Media Reporter"></b></span>
        <?php
      }
      if ($row['developer'] == '1') {
        ?>
        <span class="cTooltip"><i class='fas fa-code'></i><b title="Developer"></b></span>
        <?php
      }
      if ($row['permRole'] == '2') {
        ?>
        <span class="cTooltip"><i class='far fa-eye'></i><b title="Senior Staff"></b></span>
        <?php
      }
      if ($row['permRole'] == '3') {
        ?>
        <span class="cTooltip"><i class='fas fa-cog'></i><b title="Manager"></b></span>
        <?php
      }
      if ($row['permRole'] == '4') {
        ?>
        <span class="cTooltip"><i class='fas fa-key'></i><b title="Administrator"></b></span>
        <?php
      }
      if ($row['permRole'] >= '5') {
        ?>
        <span class="cTooltip"><i class='fas fa-money-check'></i><b title="Owner"></b></span>
        <?php
      }
      if ($row['inactive'] == 'true') {
        ?>
        <span class="cTooltip"><i class='fas fa-circle-notch'></i><b title="Pending/Suspended"></b></span>
        <?php
      }
      if ($row['trial'] == '1') {
        ?>
        <span class="cTooltip"><i class="fas fa-clipboard-list"></i><b title="Trial"></b></span>
        <?php
      }
       ?></h2>
      <p>Joined us on the <span><?php echo $row['hired'] ?></span></p>
      <p>Region: <span><?php echo $region ?></span></p>
      <p>Discord: <span><?php echo $discord ?></span></p>
      <?php
        if ($_SESSION['loggedIn']['permRole'] >= 3) {
          ?>
          <div class="form-group" style="float: right;">
            <a href="Manager.EditUser?id=<?php echo $id ?>" class="web-page" id='new' onclick="closeProfile()"><button class="profile-close-button btn btn-dark mr-2">Edit User</button></a>
            <a href="Manager.DeleteUser?id=<?php echo $id ?>" class="web-page" onclick="closeProfile()"><button class="profile-close-button btn btn-light">Delete User</button></a>
          </div>
          <?php
        }
      ?>
    </div>
  <?php
}
 ?>
