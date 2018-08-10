<?php
  $runfile = 'http://api.yeetdev.com:3000/infinite/stats';
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
  $stats = $array['icestats']['source'][0];
  if ($array['icestats']['source'][1]['server_name'] != null) {
    $stats = $array['icestats']['source'][1];
  }
  if ($_GET['specific'] == 'listeners') {
    echo $stats['listeners'];
  }
?>
