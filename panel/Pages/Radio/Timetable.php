<?php
$perm = 1;
$media = 0;
$radio = 1;
$dev = 0;
$title = "Timetable";
include('../../includes/header.php');
include('../../includes/config.php');
date_default_timezone_set('Europe/London');
$date = date( 'N' ) - 1;
if ($date == 0) {
  $day0 = "show";
}
if ($date == 1) {
  $day1 = "show";
}
if ($date == 2) {
  $day2 = "show";
}
if ($date == 3) {
  $day3 = "show";
}
if ($date == 4) {
  $day4 = "show";
}
if ($date == 5) {
  $day5 = "show";
}
if ($date == 6) {
  $day6 = "show";
}
$currentHour = date( 'H' );
$currentDay = $date;
 ?>
<style>
.card-title-right {
  float: right !important;
  margin-top: -52px;
  color: #fff;
  font-size: 23px;
}
.card-title-right .badge {
  font-size: 21px;
}
.autoClose {
    background: transparent !important;
}
</style>
    <div class="fullTT">
    <div class="timetable">
      <a data-toggle="collapse" href="#tt-0" class="cShow" aria-expanded="false" aria-controls="tt-0">
        <h1 class="card-title timetable-title">Monday</h1>
      </a>
      <div class="collapse autoClose <?php echo $day0 ?>" id="tt-0">
      <div class="row">
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '0' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

                  $own = false;
                  if ($row2 != null) {
                    $color = 'tnone';
                    if ($row2['permRole'] == 1) {
                      $color = 'tdstaff-text';
                    }
                    if ($row2['permRole'] == 2) {
                      $color = 'tsstaff-text';
                    }
                    if ($row2['permRole'] == 3) {
                      $color = 'tmanager-text';
                    }
                    if ($row2['permRole'] == 4) {
                      $color = 'tadmin-text';
                    }
                    if ($row2['permRole'] == 6) {
                      $color = 'towner-text';
                    }
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 4) {
                  $op = "timetable-na";
                  $opT = "NA";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
              if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
                ?>
                  <h1 class="nowTime">NOW!</h1>
                <?php
                } else {
                 ?>
                  <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
                 <?php
                  }
              ?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '0' AND timestart >= '06' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
                $start = $row['timestart'];
                $day = $row['day'];
                $disabled = "book";
                if ($start < $currentHour && $day == $currentDay) {
                  $disabled = 'disabled';
                }
                if ($day < $currentDay) {
                  $disabled = 'disabled';
                }
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
                    $booked = 'Cover';
                  } else {
                    $booked = 'Book';
                  }
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
                    $booked = 'Cover';
                  } else {
                    $booked = 'Book';
                  }
                  $button = true;
                }
                if ($row['timestart'] > 8) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '0' AND timestart >= '12' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] == 12) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else if ($row['timestart'] == 17) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '0' AND timestart >= '18' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 21) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
      </div>
    </div></div>
    <div class="timetable">
      <a data-toggle="collapse" href="#tt-1" class="cShow" aria-expanded="false" aria-controls="tt-1">
        <h1 class="card-title timetable-title">Tuesday</h1>
      </a>
      <div class="collapse autoClose <?php echo $day1 ?>" id="tt-1">
      <div class="row">
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '1' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

                  $own = false;
                  if ($row2 != null) {
                    $color = 'tnone';
                    if ($row2['permRole'] == 1) {
                      $color = 'tdstaff-text';
                    }
                    if ($row2['permRole'] == 2) {
                      $color = 'tsstaff-text';
                    }
                    if ($row2['permRole'] == 3) {
                      $color = 'tmanager-text';
                    }
                    if ($row2['permRole'] == 4) {
                      $color = 'tadmin-text';
                    }
                    if ($row2['permRole'] == 6) {
                      $color = 'towner-text';
                    }
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 4) {
                  $op = "timetable-na";
                  $opT = "NA";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '1' AND timestart >= '06' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] > 8) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '1' AND timestart >= '12' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] == 12) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else if ($row['timestart'] == 17) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '1' AND timestart >= '18' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 21) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
      </div>
    </div></div>
    <div class="timetable">
      <a data-toggle="collapse" href="#tt-2" class="cShow" aria-expanded="false" aria-controls="tt-2">
        <h1 class="card-title timetable-title">Wednesday</h1>
      </a>
      <div class="collapse autoClose <?php echo $day2 ?>" id="tt-2">
      <div class="row">
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '2' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

                  $own = false;
                  if ($row2 != null) {
                    $color = 'tnone';
                    if ($row2['permRole'] == 1) {
                      $color = 'tdstaff-text';
                    }
                    if ($row2['permRole'] == 2) {
                      $color = 'tsstaff-text';
                    }
                    if ($row2['permRole'] == 3) {
                      $color = 'tmanager-text';
                    }
                    if ($row2['permRole'] == 4) {
                      $color = 'tadmin-text';
                    }
                    if ($row2['permRole'] == 6) {
                      $color = 'towner-text';
                    }
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 4) {
                  $op = "timetable-na";
                  $opT = "NA";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '2' AND timestart >= '06' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] > 8) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '2' AND timestart >= '12' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] == 12) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else if ($row['timestart'] == 17) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '2' AND timestart >= '18' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 21) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
      </div>
    </div></div>
    <div class="timetable">
      <a data-toggle="collapse" href="#tt-3" class="cShow" aria-expanded="false" aria-controls="tt-3">
        <h1 class="card-title timetable-title">Thursday</h1>
      </a>
      <div class="collapse autoClose <?php echo $day3 ?>" id="tt-3">
      <div class="row">
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '3' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

                  $own = false;
                  if ($row2 != null) {
                    $color = 'tnone';
                    if ($row2['permRole'] == 1) {
                      $color = 'tdstaff-text';
                    }
                    if ($row2['permRole'] == 2) {
                      $color = 'tsstaff-text';
                    }
                    if ($row2['permRole'] == 3) {
                      $color = 'tmanager-text';
                    }
                    if ($row2['permRole'] == 4) {
                      $color = 'tadmin-text';
                    }
                    if ($row2['permRole'] == 6) {
                      $color = 'towner-text';
                    }
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 4) {
                  $op = "timetable-na";
                  $opT = "NA";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '3' AND timestart >= '06' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
  $booked = $row2['username'];
} else {
  $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
}
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] > 8) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '3' AND timestart >= '12' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
  $booked = $row2['username'];
} else {
  $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
}
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] == 12) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else if ($row['timestart'] == 17) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '3' AND timestart >= '18' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 21) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
      </div>
    </div></div>
    <div class="timetable">
      <a data-toggle="collapse" href="#tt-4" class="cShow" aria-expanded="false" aria-controls="tt-4">
        <h1 class="card-title timetable-title">Friday</h1>
      </a>
      <div class="collapse autoClose <?php echo $day4 ?>" id="tt-4">
      <div class="row">
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '4' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

                  $own = false;
                  if ($row2 != null) {
                    $color = 'tnone';
                    if ($row2['permRole'] == 1) {
                      $color = 'tdstaff-text';
                    }
                    if ($row2['permRole'] == 2) {
                      $color = 'tsstaff-text';
                    }
                    if ($row2['permRole'] == 3) {
                      $color = 'tmanager-text';
                    }
                    if ($row2['permRole'] == 4) {
                      $color = 'tadmin-text';
                    }
                    if ($row2['permRole'] == 6) {
                      $color = 'towner-text';
                    }
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 4) {
                  $op = "timetable-na";
                  $opT = "NA";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '4' AND timestart >= '06' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] > 8) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '4' AND timestart >= '12' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] == 12) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else if ($row['timestart'] == 17) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '4' AND timestart >= '18' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 21) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
      </div>
    </div></div>
    <div class="timetable">
      <a data-toggle="collapse" href="#tt-5" class="cShow" aria-expanded="false" aria-controls="tt-5">
        <h1 class="card-title timetable-title">Saturday</h1>
      </a>
      <div class="collapse autoClose <?php echo $day5 ?>" id="tt-5">
      <div class="row">
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '5' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

                  $own = false;
                  if ($row2 != null) {
                    $color = 'tnone';
                    if ($row2['permRole'] == 1) {
                      $color = 'tdstaff-text';
                    }
                    if ($row2['permRole'] == 2) {
                      $color = 'tsstaff-text';
                    }
                    if ($row2['permRole'] == 3) {
                      $color = 'tmanager-text';
                    }
                    if ($row2['permRole'] == 4) {
                      $color = 'tadmin-text';
                    }
                    if ($row2['permRole'] == 6) {
                      $color = 'towner-text';
                    }
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 4) {
                  $op = "timetable-na";
                  $opT = "NA";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '5' AND timestart >= '06' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] > 8) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '5' AND timestart >= '12' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] == 12) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else if ($row['timestart'] == 17) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '5' AND timestart >= '18' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 21) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
      </div>
    </div></div>
    <div class="timetable" style="border-bottom: none !important;">
      <a data-toggle="collapse" href="#tt-6" class="cShow" aria-expanded="false" aria-controls="tt-6">
        <h1 class="card-title timetable-title">Sunday</h1>
      </a>
      <div class="collapse autoClose <?php echo $day6 ?>" id="tt-6">
      <div class="row">
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '6' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

                  $own = false;
                  if ($row2 != null) {
                    $color = 'tnone';
                    if ($row2['permRole'] == 1) {
                      $color = 'tdstaff-text';
                    }
                    if ($row2['permRole'] == 2) {
                      $color = 'tsstaff-text';
                    }
                    if ($row2['permRole'] == 3) {
                      $color = 'tmanager-text';
                    }
                    if ($row2['permRole'] == 4) {
                      $color = 'tadmin-text';
                    }
                    if ($row2['permRole'] == 6) {
                      $color = 'towner-text';
                    }
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 4) {
                  $op = "timetable-na";
                  $opT = "NA";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '6' AND timestart >= '06' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] > 8) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '6' AND timestart >= '12' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] == 12) {
                  $op = "timetable-oc";
                  $opT = "OC";
                } else if ($row['timestart'] == 17) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="timetable-group">
            <?php

              $stmt = $conn->prepare("SELECT * FROM timetable WHERE day = '6' AND timestart >= '18' LIMIT 6");
              $stmt->execute();

              foreach($stmt as $row) {
                $booked = $row['booked'];
$start = $row['timestart'];
$day = $row['day'];
$disabled = "book";
if ($start < $currentHour && $day == $currentDay) {
  $disabled = 'disabled';
}
if ($day < $currentDay) {
  $disabled = 'disabled';
}
                if ($booked != '0') {
                  $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$booked'");
                  $stmt->execute();
                  $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
                  if ($row2['permRole'] == 1) {
                    $color = 'tdstaff-text';
                  }
                  if ($row2['permRole'] == 2) {
                    $color = 'tsstaff-text';
                  }
                  if ($row2['permRole'] == 3) {
                    $color = 'tmanager-text';
                  }
                  if ($row2['permRole'] == 4) {
                    $color = 'tadmin-text';
                  }
                  if ($row2['permRole'] == 5 || $row['permRole'] == 6) {
                    $color = 'towner-text';
                  }
                  $own = false;
                  if ($row2 != null) {
                    if ($row['booked_type'] == '0') {
                      $booked = $row2['username'];
                    } else {
                      $booked = '<span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> ' . $row2['username'];
                    }
                    $button = false;
                    if ($row2['id'] == $_SESSION['loggedIn']['id']) {
                      $own = true;
                    }
                  } else {
                    if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                    $button = true;
                  }
                } else {
                  if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  $booked = 'Cover';
} else {
  $booked = 'Book';
}
                  $button = true;
                }
                if ($row['timestart'] < 21) {
                  $op = "timetable-eu";
                  $opT = "EU";
                } else {
                  $op = '';
                }
                ?>
            <div class="timetable-item <?php echo $op ?>">
              <?php
if ($row['timestart'] == $currentHour && $row['day'] == $currentDay) {
  ?>
    <h1 class="nowTime">NOW!</h1>
  <?php
  } else {
   ?>
    <h1><?php echo $row['timestart'] ?>:00-<?php echo $row['timeend'] ?>:00</h1>
   <?php
    }
?>
              <?php
              if ($op != null) {
                ?>
                <h1 class='timetable-region'><?php echo $opT ?></h1>
                <?php
              }
              if ($button) {
                ?>
                <div><div class="<?php echo $disabled ?> timetable-button timetable-button-u" data-id="<?php echo $row['id'] ?>"><p><?php echo $booked ?></p></div></div>
                <?php
              } else {
                if ($own) {
                  ?>
                  <div>
                  <div class="row timetable-in">
                    <div class='col-10'>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                    </div>
                    <div class='col-2' style="padding: 0;">
                      <div class="timetable-manage-o">
                        <div class="row">
                          <div class='col-6'>
                            <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                  <?php
                } else {
                  if ($_SESSION['loggedIn']['permRole'] >= 3) {
                    ?>
                    <div>
                    <div class="row timetable-in">
                      <div class='col-10'>
                        <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button"><p><?php echo $booked ?></p></div>
                      </div>
                      <div class='col-2' style="padding: 0;">
                        <div class="timetable-manage-o">
                          <div class="row">
                            <div class='col-6'>
                              <i data-id="<?php echo $row['id'] ?>" class="unBook far fa-times-circle"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <?php
                  } else {
                    ?>
                      <div onclick='loadProfile(<?php echo $row2['id']?>)' class="<?php echo $color ?> userLink timetable-button timetable-button-u"><p><?php echo $booked ?></p></div>
                    <?php
                  }
                }
              }
               ?>
            </div>
            <?php
              }
             ?>
          </div>
        </div>
      </div>
    </div></div>
  </div>
</div>

<script>
$(".cShow").click( function(e) {
    jQuery('.autoClose').collapse('hide');
});
$(document).on('click','.book',function(){
  var object = $(this);
  var thing = this;
  object.html('<i class="fas fa-circle-notch fa-spin" style="color: #fff; padding: 8px; font-size: 13px;"></i>');
  $.ajax({
      type: 'POST',
      url: './scripts/bookSlot.php',
      data: {id: thing.dataset.id}
  }).done(function(response) {
    console.log(response);
    if (response == 'booked') {
      object.html("<p><?php echo $_SESSION['loggedIn']['username']?></p>");
      object.parent().html(`<div class="row timetable-in">
        <div class='col-10'>
          <div onclick='loadProfile(<?php echo $_SESSION['loggedIn']['id']?>)' class="userLink timetable-button"><p><?php echo $_SESSION['loggedIn']['username'] ?></p></div>
        </div>
        <div class="col-2" style="padding: 0;">
          <div class="timetable-manage-o">
            <div class="row">
              <div class="col-6">
                <i data-id="${thing.dataset.id}" class="unBook far fa-times-circle"></i>
              </div>
            </div>
          </div>
        </div>
      </div>`);
    } else if (response == 'covered') {
      object.html("<p><?php echo $_SESSION['loggedIn']['username']?></p>");
      object.parent().html(`<div class="row timetable-in">
        <div class='col-10'>
          <div onclick='loadProfile(<?php echo $_SESSION['loggedIn']['id']?>)' class="userLink timetable-button"><p><span class="cTooltip"><i class="far fa-life-ring"></i><b title="Covered Slot"></b></span> <?php echo $_SESSION['loggedIn']['username'] ?></p></div>
        </div>
        <div class="col-2" style="padding: 0;">
          <div class="timetable-manage-o">
            <div class="row">
              <div class="col-6">
                <i data-id="${thing.dataset.id}" class="unBook far fa-times-circle"></i>
              </div>
            </div>
          </div>
        </div>
      </div>`);
    } else {
      object.html('<p><i class="fas fa-times" style="color: #fff; font-size: 13px;"></i> Failed</p>');
    }
  }).fail(function (response) {
    object.html('<p><i class="fas fa-times" style="color: #fff; font-size: 13px;"></i> Failed</p>');
  });
 });
$(document).on('click','.unBook',function(){
  var object = $(this);
  var thing = this;
  $.ajax({
      type: 'POST',
      url: './scripts/unbookSlot.php',
      data: {id: thing.dataset.id}
  }).done(function(response) {
    console.log(response);
    if (response == 'unbooked') {
      object.parent().parent().parent().parent().parent().parent().html(`<div><div class="timetable-button timetable-button-u book" data-id="${thing.dataset.id}"><p>Book</p></div></div>`);
    } else if (response == 'current'){
      object.parent().parent().parent().parent().parent().parent().html(`<div><div class="timetable-button timetable-button-u book" data-id="${thing.dataset.id}"><p>Cover</p></div></div>`);
    }
  }).fail(function (response) {

  });
});
</script>
