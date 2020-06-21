<?php
  $runfile = 'http://api.yeetdev.com:3000/power/stats';

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
     print curl_error($ch);
  }
  curl_close($ch);
  $array = json_decode($result, true);
  $stats = $array['icestats']['source'];

?>
<div class="song-name">
<p><i class="fa fa-music"></i> <?php echo $stats['title'] ?></p>
</div>
<div class="listner-info">
<p class="lis-name"><i class="fa fa-headphones"></i> <?php echo $stats['server_name'] ?></p> <p class="lis-number"><i class="fa fa-users"></i> <?php echo $stats['listeners'] ?></p>
</div>
