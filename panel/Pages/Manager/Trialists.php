<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
<div class="card" id="users">
  <div class="card-body" >
    <h1 class="card-title">Manage Trialists</h1>

    <!-- Department Staff -->

    <div class="card usersCard">
      <div class="card-body" style="background: #020b4a;">
        <a data-toggle="collapse" href="#sf-dj" aria-expanded="false" aria-controls="sf-dj">
          <h1 class="card-title usersTitle"><i class="fa fa-user"></i> Department Staff</h1>
        </a>
        <div class="collapse" id="sf-dj">
          <div class="table-responsive">
            <table class="table usersTable">
              <thead>
                <tr>
                  <th>
                    User
                  </th>
                  <th>
                    Username
                  </th>
                  <th>
                    Roles
                  </th>
                  <th>
                    Hired
                  </th>
                  <th>
                    Pass
                  </th>
                  <th>
                    Fail
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '1' AND trial = '1' ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"><?php echo $row['username'] ?></span>
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
                           ?>
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" data-uid="<?php echo $row['id'] ?>" class="passUser btn btn-success btn-fw">Pass</button>
                        </td>
                        <td>
                          <button type="button" data-uid="<?php echo $row['id'] ?>" class="failUser btn btn-danger btn-fw">Fail</button>
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

    <div class="card usersCard">
      <div class="card-body" style="background: #8f05a0;">
        <a data-toggle="collapse" href="#sf-me" aria-expanded="false" aria-controls="sf-me">
          <h1 class="card-title usersTitle"><i class="far fa-eye"></i> Senior Staff</h1>
        </a>
        <div class="collapse" id="sf-me">
          <div class="table-responsive">
            <table class="table usersTable">
              <thead>
                <tr>
                  <th>
                    User
                  </th>
                  <th>
                    Username
                  </th>
                  <th>
                    Roles
                  </th>
                  <th>
                    Hired
                  </th>
                  <th>
                    Pass
                  </th>
                  <th>
                    Fail
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '2' AND trial = '1' ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"><?php echo $row['username'] ?></span>
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

                          ?>
                          <span class="cTooltip"><i class='far fa-eye'></i><b title="Senior Staff"></b></span>
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" data-uid="<?php echo $row['id'] ?>" class="passUser btn btn-success btn-fw">Pass</button>
                        </td>
                        <td>
                          <button type="button" data-uid="<?php echo $row['id'] ?>" class="failUser btn btn-danger btn-fw">Fail</button>
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
    if ($_SESSION['loggedIn']['permRole'] > 3) {
     ?>

    <div class="card usersCard">
      <div class="card-body" style="background: #006729;">
        <a data-toggle="collapse" href="#sf-ma" aria-expanded="false" aria-controls="sf-ma">
          <h1 class="card-title usersTitle"><i class="menu-icon fas fa-cog"></i> Manager</h1>
        </a>
        <div class="collapse" id="sf-ma">
          <div class="table-responsive">
            <table class="table usersTable">
              <thead>
                <tr>
                  <th>
                    User
                  </th>
                  <th>
                    Username
                  </th>
                  <th>
                    Roles
                  </th>
                  <th>
                    Hired
                  </th>
                  <th>
                    Pass
                  </th>
                  <th>
                    Fail
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '3' AND trial = '1' ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"><?php echo $row['username'] ?></span>
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

                          ?>
                          <span class="cTooltip"><i class='fas fa-cog'></i><b title="Manager"></b></span>
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" data-uid="<?php echo $row['id'] ?>" class="passUser btn btn-success btn-fw">Pass</button>
                        </td>
                        <td>
                          <button type="button" data-uid="<?php echo $row['id'] ?>" class="failUser btn btn-danger btn-fw">Fail</button>
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
    if ($_SESSION['loggedIn']['permRole'] > 4) {
     ?>

    <div class="card usersCard">
      <div class="card-body" style="background: #b30000;">
        <a data-toggle="collapse" href="#sf-ad" aria-expanded="false" aria-controls="sf-ad">
          <h1 class="card-title usersTitle"><i class="menu-icon fas fa-key"></i> Administrator</h1>
        </a>
        <div class="collapse" id="sf-ad">
          <div class="table-responsive">
            <table class="table usersTable">
              <thead>
                <tr>
                  <th>
                    User
                  </th>
                  <th>
                    Username
                  </th>
                  <th>
                    Roles
                  </th>
                  <th>
                    Hired
                  </th>
                  <th>
                    Pass
                  </th>
                  <th>
                    Fail
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '4' AND trial = '1' ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <span onclick="loadProfile('<?php echo $row['id'] ?>')" class="userLink"><?php echo $row['username'] ?></span>
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

                          ?>
                          <span class="cTooltip"><i class='fas fa-key'></i><b title="Administrator"></b></span>
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" data-uid="<?php echo $row['id'] ?>" class="passUser btn btn-success btn-fw">Pass</button>
                        </td>
                        <td>
                          <button type="button" data-uid="<?php echo $row['id'] ?>" class="failUser btn btn-danger btn-fw">Fail</button>
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
     $('.passUser').click(function(ev) {
         ev.preventDefault();
         ev.stopPropagation();
         var object = $(this);
         var thing = this;
         $.ajax({
             type: 'POST',
             url: './scripts/passUser.php',
             data: {id: thing.dataset.uid}
         }).done(function(response) {
           console.log(response);
           if (response == 'passed') {
             object.parent().parent().fadeOut();
           } else {
             console.log('error');
           }
         }).fail(function (response) {
            console.log('error');
         });
     });
     $('.failUser').click(function(ev) {
         ev.preventDefault();
         ev.stopPropagation();
         var object = $(this);
         var thing = this;
         $.ajax({
             type: 'POST',
             url: './scripts/failUser.php',
             data: {id: thing.dataset.uid}
         }).done(function(response) {
           console.log(response);
           if (response == 'passed') {
             object.parent().parent().fadeOut();
           } else {
             console.log('error');
           }
         }).fail(function (response) {
            console.log('error');
         });
     });
     </script>



  </div>
</div>
