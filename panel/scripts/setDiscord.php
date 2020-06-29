<?php
include('../includes/config.php');
$api = $_POST['api'];
if ($api !== "q1tbDYr9M4rCDM5Nos09Wrg7UlKpSunv9WM3BG9V9N5qeVE") {
  echo "0";
  exit();
}
$id = $_POST['user'];
$discordID = $_POST['discord_id'];
$discordUsername = $_POST['discord_username'];
$stmt = $conn->prepare("UPDATE users SET discord = :discord, discord_id = :discordID WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':discord', $discordUsername);
$stmt->bindParam(':discordID', $discordID);
$stmt->execute();
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$details = $stmt->fetch(PDO::FETCH_ASSOC);
$url = "http://31.220.56.47:3201/api/keyfm/verifyStaff";
$fields = [
    'api' => "q1tbDYr9M4rCDM5Nos09Wrg7UlKpSunv9WM3BG9V9N5qeVE",
    'username' => $details['username'],
    'discordId'=> $discordID
];

$fields_string = http_build_query($fields);

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
echo "1";
?>
