<?php
include('../includes/config.php');
$username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
$username = strtoupper($username);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
/*session_start();
$loggedIn = [
  "username" => "Parker",
  "id" => "1",
  "avatarURL" => "https://cdn.discordapp.com/avatars/212630637365035009/46b40445f96d41510c90af845d59856b.png",
  "permRole" => "6",
  "radio" => "1",
  "developer" => "1",
  "media" => "1",
  "displayRole" => "Developer"
];
$_SESSION['loggedIn'] = $loggedIn;
echo "good";*/
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

        session_start();
        $loggedIn = [
          "username" => $details['username'],
          "id" => $details['id'],
          "avatarURL" => $details['avatarURL'],
          "permRole" => $details['permRole'],
          "radio" => $details['radio'],
          "developer" => $details['developer'],
          "media" => $details['media'],
          "trial" => $details['trial'],
          "displayRole" => $details['displayRole']
        ];
        $_SESSION['loggedIn'] = $loggedIn;
        $stmt = $conn->prepare("UPDATE users SET lastLogin = CURRENT_TIMESTAMP WHERE UPPER(username) = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        echo "good";
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
