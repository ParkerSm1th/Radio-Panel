<?php
session_set_cookie_params(0, '/', '.keyfm.net');
include('../includes/config.php');
include('../includes/notifications.php');
$username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
$username = strtoupper($username);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

// Check if username/email exists in the database
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
  $ip = $_SERVER['REMOTE_ADDR'];
}
  $stmt = $conn->prepare("SELECT * FROM users WHERE UPPER(username) = :username");
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  if($stmt->rowCount() > 0) {
    // Check if the password exists in the database
    $stmt = $conn->prepare("SELECT pass FROM users WHERE UPPER(username) = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $db_pass_record = $stmt->fetch(PDO::FETCH_ASSOC);


    if(password_verify($password, $db_pass_record['pass'])) {

      $stmt = $conn->prepare("SELECT inactive FROM users WHERE UPPER(username) = :username");
      $stmt->bindParam(':username', $username);
      $stmt->execute();
      $active_status = $stmt->fetch(PDO::FETCH_ASSOC);

      if($active_status['inactive'] == 'true') {

        // If yes display error
        echo "suspend";
        exit();

      } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE UPPER(username) = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $details = $stmt->fetch(PDO::FETCH_ASSOC);
        $usernameReal = $details['username'];
        if ($details['discord'] !== "" && $ip !== $details['lastLoginIP']) {
          $stmt = $conn->prepare("UPDATE users SET newIP = :ip WHERE id = :id");
          $stmt->bindParam(':id', $details['id']);
          $stmt->bindParam(':ip', $ip);
          $stmt->execute();
          echo "discord";
          $url = "http://31.220.56.47:3201/api/keyfm/verifyIP";
          $fields = [
              'api' => "q1tbDYr9M4rCDM5Nos09Wrg7UlKpSunv9WM3BG9V9N5qeVE",
              'username' => $details['username'],
              'discordId'=> $details['discord_id']
          ];

          $fields_string = http_build_query($fields);

          $ch = curl_init();

          curl_setopt($ch,CURLOPT_URL, $url);
          curl_setopt($ch,CURLOPT_POST, true);
          curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

          curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

          $result = curl_exec($ch);
          session_start();
          $_SESSION['discordUserID'] = $details['id'];
          exit();
        } else {
          session_start();
          $loggedIn = [
            "username" => $details['username'],
            "id" => $details['id'],
            "avatarURL" => $details['avatarURL'],
            "permRole" => $details['permRole'],
            "radio" => $details['radio'],
            "developer" => $details['developer'],
            "media" => $details['media'],
            "social" => $details['social'],
            "trial" => $details['trial'],
            "displayRole" => $details['displayRole'],
            "discord" => $details['discord'],
            "discordID" => $details['discord_id']
          ];
          $_SESSION['loggedIn'] = $loggedIn;
          if ($details['radio'] == 1) {
            $stmt = $conn->prepare("SELECT * FROM timetable WHERE booked = :id");
            $stmt->bindParam(':id', $details['id']);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count < 3) {
              $stmt = $conn->prepare("SELECT * FROM notifications WHERE userID = :id AND header = 'Required Slots Reminder' AND active = '1'");
              $stmt->bindParam(':id', $details['id']);
              $stmt->execute();
              $count2 = $stmt->rowCount();
              if ($count2 == 0) {
                $userID = $details['id'];
                $type = "warning";
                $header = "Required Slots Reminder";
                $content = "Remember to DJ at least 3 slots this week!";
                $icon = "clock";
                $iconSubmit = "far fa-" . $icon;
                $stmt = $conn->prepare("INSERT INTO notifications (userID, type, header, content, icon) VALUES (:userID, :type, :header, :content, :icon)");
                $stmt->bindParam(':userID', $userID);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':header', $header);
                $stmt->bindParam(':content', $content);
                $stmt->bindParam(':icon', $iconSubmit);
                $stmt->execute();

              }
            }
          }
          if ($ip == $details['lastLoginIP']) {
            $stmt = $conn->prepare("UPDATE users SET lastLogin = CURRENT_TIMESTAMP, lastLoginIP = :ip WHERE UPPER(username) = :username");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':ip', $ip);
            $stmt->execute();
          }
          $sessionID = session_id();
          $userD = $details['id'];

          $stmt = $conn->prepare("DELETE FROM sessions WHERE user = :user");
          $stmt->bindParam(':user', $userD);
          $stmt->execute();

          $stmt = $conn->prepare("INSERT INTO sessions (user, session, times) VALUES (:user, :session, CURRENT_TIMESTAMP)");
          $stmt->bindParam(':user', $userD);
          $stmt->bindParam(':session', $sessionID);
          $stmt->execute();
          $_SESSION['logout'] = false;
          echo "good";
        }
      }

    } else {
      $logMessage = "Attempted login. Login failed (Wrong Password)";
      $stmt = $conn->prepare("INSERT INTO panel_log (name, ip, times, action) VALUES (:name, :ip,CURRENT_TIMESTAMP, :action)");
      $stmt->bindParam(':name', $username);
      $stmt->bindParam(':ip', $ip);
      $stmt->bindParam(':action', $logMessage);
      $stmt->execute();
      echo "error";
    }
  } else {
    $logMessage = "Attempted login. Login failed (Invalid User)";
    $stmt = $conn->prepare("INSERT INTO panel_log (name, ip, times, action) VALUES (:name, :ip,CURRENT_TIMESTAMP, :action)");
    $stmt->bindParam(':name', $username);
    $stmt->bindParam(':ip', $ip);
    $stmt->bindParam(':action', $logMessage);
    $stmt->execute();
    echo "error";
  }
?>
