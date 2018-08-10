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
    <h1 class="card-title">Manage Staff</h1>

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
                    Profile
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '1' ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <?php echo $row['username'] ?>
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
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" onclick="loadProfile('<?php echo $row['id'] ?>')" class="btn btn-light btn-fw">Profile</button>
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
                    Profile
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '2' ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <?php echo $row['username'] ?>
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
                          <span class="cTooltip"><i class='far fa-eye'></i><b title="Senior Staff"></b></span>
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" onclick="loadProfile('<?php echo $row['id'] ?>')" class="btn btn-light btn-fw">Profile</button>
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
    if ($_SESSION['loggedIn']['permRole'] >= 3) {
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
                    Profile
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '3' ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <?php echo $row['username'] ?>
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
                          <span class="cTooltip"><i class='fas fa-cog'></i><b title="Manager"></b></span>
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" onclick="loadProfile('<?php echo $row['id'] ?>')" class="btn btn-light btn-fw">Profile</button>
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
    if ($_SESSION['loggedIn']['permRole'] >= 4) {
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
                    Profile
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = '4' ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <?php echo $row['username'] ?>
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
                          <span class="cTooltip"><i class='fas fa-key'></i><b title="Administrator"></b></span>
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" onclick="loadProfile('<?php echo $row['id'] ?>')" class="btn btn-light btn-fw">Profile</button>
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

    <!-- Owner -->

    <?php
    if ($_SESSION['loggedIn']['permRole'] >= 5) {
     ?>

    <div class="card usersCard">
      <div class="card-body" style="background: #7b0000;">
        <a data-toggle="collapse" href="#sf-o" aria-expanded="false" aria-controls="sf-o">
          <h1 class="card-title usersTitle"><i class="menu-icon fas fa-money-check"></i> Ownership</h1>
        </a>
        <div class="collapse" id="sf-o">
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
                    Profile
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM users WHERE permRole = 6 ORDER BY id");
                  $stmt->execute();

                  foreach($stmt as $row) {
                    $id = $row['id'];

                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?php echo $row['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                        </td>
                        <td>
                          <?php echo $row['username'] ?>
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
                            if ($row['developer'] == '1') {
                              ?>
                              <span class="cTooltip"><i class='fas fa-code'></i><b title="Developer"></b></span>
                              <?php
                            }

                          ?>
                          <span class="cTooltip"><i class='fas fa-money-check'></i><b title="Owner"></b></span>
                        </td>
                        <td>
                          <?php echo $row['hired'] ?>
                        </td>
                        <td>
                          <button type="button" onclick="loadProfile('<?php echo $row['id'] ?>')" class="btn btn-light btn-fw">Profile</button>
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


  </div>
</div>
