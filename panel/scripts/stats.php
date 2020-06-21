<?php
  include('../includes/config.php');
  $runfile = 'http://31.220.56.47:3200/stats';
  //  Initiate curl
  $ch = curl_init();
  // Disable SSL verification
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  // Will return the response, if false it print the response
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // Set the url
  curl_setopt($ch, CURLOPT_URL,$runfile);
  // Execute
  curl_setopt($ch, CURLOPT_VERBOSE, true);
  $result=curl_exec($ch);
  if (curl_errno($ch)) {
     echo "Error";
     exit();
  }
  curl_close($ch);
  $stats = json_decode($result, true);

  if ($stats['success'] !== true) {
    echo "Error";
    exit();
  }
  if ($_GET['specific'] == 'listeners') {
    if ($_GET['icon'] == true) {
      if ($stats['listeners']['peak'] > $stats['listeners']['current']) {
        echo $stats['listeners']['current'] . ' <i class="text-danger fas fa-caret-down"></i>';
      } else if ($stats['listeners']['peak'] < $stats['listeners']['current']) {
        echo $stats['listeners']['current'] . ' <i class="text-success fas fa-caret-up"></i>';
      }
      if ($stats['listeners']['peak'] == $stats['listeners']['current']) {
        echo $stats['listeners']['current'] . ' <i class="text-success fas fa-caret-up"></i>';
      }
    } else {
      echo $stats['listeners']['current'];
    }
  }

  if ($_GET['specific'] == 'likes') {
    if ($stats['currentDJ']['autoDJ'] == true) {
      echo 0;
      exit();
    }
    $current = $stats['currentDJ']['id'];
    $time = strtotime("-1 hour");
    $stmt = $conn->prepare("SELECT * FROM likes WHERE times > :time AND dj = :dj");
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':dj', $current);
    $stmt->execute();
    $count = $stmt->rowCount();
    echo $count;
    exit();
  }

  if ($_GET['specific'] == 'time') {
    $math = 60 - date('i');
    echo $math . "mins";
  }

  if ($_GET['specific'] == 'listenersPeak') {
    echo $stats['listeners']['peak'];
  }
?>
