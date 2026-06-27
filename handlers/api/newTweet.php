<?php

use RadioPanel\Core\Auth;

$user = Auth::user();
if ((string) ($user['social'] ?? '0') !== '1' && (int) ($user['permRole'] ?? 0) < 3) {
  http_response_code(403);
  echo 'forbidden';
  exit;
}

date_default_timezone_set('Europe/London');
$date = date('Y-d-m H:i:s');
$contentRaw = $_POST['content'] . "\n\n- " . $user['username'];
$tweetID = "null";
$content = strtr($contentRaw, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
$stmt = $conn->prepare("INSERT INTO tweets (user, content, times, twitter_id, deleted) VALUES (:user, :content, :times, :tweet, 0)");
$stmt->bindParam(':content', $content);
$stmt->bindParam(':times', $date);
$stmt->bindParam(':tweet', $tweetID);
$stmt->bindValue(':user', (int) $user['id'], PDO::PARAM_INT);
$stmt->execute();
echo "tweeted";
?>
