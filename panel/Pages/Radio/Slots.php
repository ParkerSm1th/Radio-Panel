<?php
  $perm = 1;
  $media = 0;
  $radio = 1;
  $dev = 1;
  include('../../includes/header.php');
  include('../../includes/config.php');
  date_default_timezone_set('Europe/London');
  $today_date = date( 'N' ) - 1;
  $next_date = date( 'N' );

  $now_hour = date( 'H' ) ;
  $next_hour = date('H') + 1;
  $later_hour = $next_hour + 1;
  if ( $next_hour == 24 ) {
    $later_date = $today_date + 1;
    $later_hour = "0";
  }
  else {
    $later_date = $today_date;
  }
  echo $now_hour . "<br>";
  echo $next_hour . "<br>";
  echo $later_hour . "<br>";
  echo $today_date . "<br>";
 ?>
