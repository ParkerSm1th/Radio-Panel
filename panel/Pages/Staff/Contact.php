<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 0;
$pending = 1;
$title = "Staff Contact List";
include('../../includes/header.php');
include('../../includes/config.php');
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$count = $stmt->rowCount();
 ?>
 <style>
  
 </style>

    <!-- Department Staff -->

    <div class="card usersCard m-b-10">
      <div class="card-body dstaff-background">
        <a data-toggle="collapse" href="#sf-dj" aria-expanded="false" class="cShow" aria-controls="sf-dj">
          <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '1'");
          $stmt->execute();
          $count = $stmt->rowCount();
           ?>
          <h1 class="card-title usersTitle"><i class="fa fa-user"></i> Department Staff</h1>
        </a>
        <div class="collapse autoClose p-a-n " id="sf-dj">
          <div class="table-responsive">
            <table class="table usersTable">
              
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '1' ORDER BY username");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                           <img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/square.png'" alt="image"><span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"> <?php echo $row['username'] ?></span>
                        </td>
                        <td>
                          <?php
                            if ($row['radio'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
                              <?php
                            }
                            if ($row['media'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-newspaper'></i><b title="News Reporter"></b></span>
                              <?php
                            }
                            if ($row['social'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-share-alt'></i><b title="Media Reporter"></b></span>
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

                          ?>
                        </td>
                        <td>
                          <?php echo $row['discord'] ?>
                        </td>
                       
                      </tr>
                      <?php
                    }

                  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Senior Staff -->

    <div class="card usersCard m-b-10">
      <div class="card-body" style="background: #9a1790;">
        <a data-toggle="collapse" href="#sf-me" aria-expanded="false" class="cShow" aria-controls="sf-me">
          <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '2'");
          $stmt->execute();
          $count = $stmt->rowCount();
           ?>
          <h1 class="card-title usersTitle"><i class="far fa-eye"></i> Senior Staff</h1>
        </a>
        <div class="collapse autoClose p-a-n" id="sf-me">
          <div class="table-responsive">
            <table class="table usersTable">
              
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '2' ORDER BY username");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                           <img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/square.png'" alt="image"><span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"> <?php echo $row['username'] ?></span>
                        </td>
                        <td>
                          <?php
                            if ($row['radio'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
                              <?php
                            }
                            if ($row['media'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-newspaper'></i><b title="News Reporter"></b></span>
                              <?php
                            }
                            if ($row['social'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-share-alt'></i><b title="Media Reporter"></b></span>
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

                          ?>
                          <span class="cTooltip"><i class='far fa-eye'></i><b title="Senior Staff"></b></span>
                        </td>
                        <td>
                          <?php echo $row['discord'] ?>
                        </td>
                       
                      </tr>
                      <?php
                    }

                  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Manager -->

    <?php
    if (true) {
     ?>

    <div class="card usersCard m-b-10">
      <div class="card-body" style="background: #006729;">
        <a data-toggle="collapse" href="#sf-ma" aria-expanded="false" class="cShow" aria-controls="sf-ma">
          <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '3'");
          $stmt->execute();
          $count = $stmt->rowCount();
           ?>
          <h1 class="card-title usersTitle"><i class="menu-icon fas fa-cog"></i> Manager</h1>
        </a>
        <div class="collapse autoClose p-a-n" id="sf-ma">
          <div class="table-responsive">
            <table class="table usersTable">
              
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '3' ORDER BY username");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                           <img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/square.png'" alt="image"><span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"> <?php echo $row['username'] ?></span>
                        </td>
                        <td>
                          <?php
                            if ($row['radio'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
                              <?php
                            }
                            if ($row['media'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-newspaper'></i><b title="News Reporter"></b></span>
                              <?php
                            }
                            if ($row['social'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-share-alt'></i><b title="Media Reporter"></b></span>
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

                          ?>
                          <span class="cTooltip"><i class='fas fa-cog'></i><b title="Manager"></b></span>
                        </td>
                        <td>
                          <?php echo $row['discord'] ?>
                        </td>
                       
                      </tr>
                      <?php
                    }

                  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <?php
    }
     ?>

    <!-- Administrator -->

    <?php
    if (true) {
     ?>

    <div class="card usersCard m-b-10">
      <div class="card-body" style="background: #b30000;">
        <a data-toggle="collapse" href="#sf-ad" aria-expanded="false" class="cShow" aria-controls="sf-ad">
          <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '4'");
          $stmt->execute();
          $count = $stmt->rowCount();
           ?>
          <h1 class="card-title usersTitle"><i class="menu-icon fas fa-key"></i> Administrator</h1>
        </a>
        <div class="collapse autoClose p-a-n" id="sf-ad">
          <div class="table-responsive">
            <table class="table usersTable">
              
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '4' ORDER BY username");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                           <img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/square.png'" alt="image"><span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"> <?php echo $row['username'] ?></span>
                        </td>
                        <td>
                          <?php
                            if ($row['radio'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
                              <?php
                            }
                            if ($row['media'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-newspaper'></i><b title="News Reporter"></b></span>
                              <?php
                            }
                            if ($row['social'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-share-alt'></i><b title="Media Reporter"></b></span>
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

                          ?>
                          <span class="cTooltip"><i class='fas fa-key'></i><b title="Administrator"></b></span>
                        </td>
                        <td>
                          <?php echo $row['discord'] ?>
                        </td>
                       
                      </tr>
                      <?php
                    }

                  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <?php
    }
     ?>

     <!-- Executive -->

     <?php
     if (true) {
      ?>

     <div class="card usersCard m-b-10">
       <div class="card-body" style="background: #d08017;">
         <a data-toggle="collapse" href="#sf-ex" aria-expanded="false" class="cShow" aria-controls="sf-ad">
           <?php
           $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '5'");
           $stmt->execute();
           $count = $stmt->rowCount();
            ?>
           <h1 class="card-title usersTitle"><i class="menu-icon fas fa-briefcase"></i> Executive</h1>
         </a>
         <div class="collapse autoClose p-a-n" id="sf-ex">
           <div class="table-responsive">
             <table class="table usersTable">
               <tbody>
                 <?php

                   $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '5' ORDER BY username");
                   $stmt->execute();

                   foreach($stmt as $row) {
                     $id = $row['id'];

                     ?>
                       <tr>
                         <td class="py-1">
                            <img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/square.png'" alt="image"><span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"> <?php echo $row['username'] ?></span>
                         </td>
                         <td>
                           <?php
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

                           ?>
                           <span class="cTooltip"><i class='fas fa-briefcase'></i><b title="Executive"></b></span>
                         </td>
                         <td>
                           <?php echo $row['discord'] ?>
                         </td>
     
                       </tr>
                       <?php
                     }

                   ?>
               </tbody>
             </table>
           </div>
         </div>
       </div>
     </div>

     <?php
     }
      ?>

    <!-- Ownership -->

    <?php
    if (true) {
     ?>

    <div class="card usersCard m-b-10">
      <div class="card-body" style="background: #8a08da;">
        <a data-toggle="collapse" href="#sf-o" aria-expanded="false" class="cShow" aria-controls="sf-o">
          <?php
          $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '6'");
          $stmt->execute();
          $count = $stmt->rowCount();
           ?>
          <h1 class="card-title usersTitle"><i class="menu-icon fas fa-money-check"></i> Ownership</h1>
        </a>
        <div class="collapse autoClose p-a-n" id="sf-o">
          <div class="table-responsive">
            <table class="table usersTable">
              
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = 6 ORDER BY username");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                           <img src="../profilePictures/<?php echo $row['avatarURL']?>" onerror="this.src='../images/square.png'" alt="image"><span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"> <?php echo $row['username'] ?></span>
                        </td>
                        <td>
                          <?php
                            if ($row['radio'] == '1') {
                              if ($row['guest'] == '1') {
                                ?>
                                  <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Guest DJ"></b></span>
                                <?php
                              } else {
                                ?>
                                  <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
                                <?php
                              }
                            }
                            if ($row['media'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-newspaper'></i><b title="News Reporter"></b></span>
                              <?php
                            }
                            if ($row['social'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fa fa-share-alt'></i><b title="Media Reporter"></b></span>
                              <?php
                            }
                            if ($row['inactive'] == 'true') {
                              ?>
                              <span class="cTooltip"><i class='fas fa-circle-notch'></i><b title="Pending/Suspended"></b></span>
                              <?php
                            }
                            if ($row['developer'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fas fa-code'></i><b title="Developer"></b></span>
                              <?php
                            }

                          ?>
                          <span class="cTooltip"><i class='fas fa-money-check'></i><b title="Owner"></b></span>
                        </td>
                        <td>
                          <?php echo $row['discord'] ?>
                        </td>
                       
                      </tr>
                      <?php
                    }

                  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <?php
    }
     ?>
<script>
$(".cShow").click( function(e) {
    jQuery('.autoClose').collapse('hide');
});
</script>
