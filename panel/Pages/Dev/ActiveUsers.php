<?php
$perm = 4;
$media = 0;
$radio = 0;
$dev = 0;
include('../../includes/header.php');
include('../../includes/config.php');
$allSessions = [];
$loggedSessions = [];
$usersSession = "";
$username = $_SESSION['loggedIn']['username'];
$sessionNames = scandir(session_save_path());
?>
<div class="card" id="users">
  <div class="card-body" >
    <h1 class="card-title">Active Users</h1>

    <!-- Department Staff -->

    <div class="card usersCard">
      <div class="card-body" style="background: #020b4a;">
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
                  Profile
                </th>
                <th>
                  Logout
                </th>
              </tr>
            </thead>
            <tbody>
              <?php

                  foreach($sessionNames as $sessionName) {
                      $sessionName = str_replace("sess_","",$sessionName);
                      if(strpos($sessionName,".") === false) { //This skips temp files that aren't sessions
                          session_id($sessionName);
                          session_start();
                          if ($loggedSessions[$_SESSION['loggedIn']['username']] == null && $_SESSION['loggedIn']['username'] != null) {
                            $allSessions[$sessionName] = $_SESSION;
                            $loggedSessions[$_SESSION['loggedIn']['username']] = $_SESSION['loggedIn'];
                            if ($_SESSION['loggedIn']['username'] == $username) {
                              $usersSession = session_id();
                            }
                  ?>
                    <tr>
                      <td class="py-1">
                        <img src="<?php echo $_SESSION['loggedIn']['avatarURL']?>" onerror="this.src='../images/Logo.png'" alt="image">
                      </td>
                      <td>
                        <?php echo $_SESSION['loggedIn']['username'] ?>
                      </td>
                      <td>
                        <?php
                          if ($_SESSION['loggedIn']['radio'] == '1') {
                            ?>
                            <span class="cTooltip"><i class='fa fa-microphone-alt'></i><b title="Radio DJ"></b></span>
                            <?php
                          }
                          if ($_SESSION['loggedIn']['media'] == '1') {
                            ?>
                            <span class="cTooltip"><i class='fa fa-newspaper'></i><b title="Media Reporter"></b></span>
                            <?php
                          }
                          if ($_SESSION['loggedIn']['developer'] == '1') {
                            ?>
                            <span class="cTooltip"><i class='fas fa-code'></i><b title="Developer"></b></span>
                            <?php
                          }
                          if ($_SESSION['loggedIn']['permRole'] == '2') {
                            ?>
                            <span class="cTooltip"><i class='far fa-eye'></i><b title="Senior Staff"></b></span>
                            <?php
                          }
                          if ($_SESSION['loggedIn']['permRole'] == '3') {
                            ?>
                            <span class="cTooltip"><i class='fas fa-cog'></i><b title="Manager"></b></span>
                            <?php
                          }
                          if ($_SESSION['loggedIn']['permRole'] == '4') {
                            ?>
                            <span class="cTooltip"><i class='fas fa-key'></i><b title="Administrator"></b></span>
                            <?php
                          }
                          if ($_SESSION['loggedIn']['permRole'] >= '5') {
                            ?>
                            <span class="cTooltip"><i class='fas fa-money-check'></i><b title="Owner"></b></span>
                            <?php
                          }
                          if ($_SESSION['loggedIn']['trial'] == '1') {
                            ?>
                            <span class="cTooltip"><i class="fas fa-clipboard-list"></i><b title="Trial"></b></span>
                            <?php
                          }
                         ?>
                      </td>
                      <td>
                        <button type="button" onclick="loadProfile('<?php echo $_SESSION['loggedIn']['id'] ?>')" class="btn btn-light btn-fw">Profile</button>
                      </td>
                      <td>
                        <button type="button" class="removeSession btn btn-danger btn-fw" data-sid="<?php echo $sessionName ?>">Logout</button>
                      </td>
                    </tr>
                    <?php
                    }
                    session_abort();
                }
            }
            session_id($usersSession);
            session_start();

                ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$('.removeSession').click(function(ev) {
    ev.preventDefault();
    ev.stopPropagation();
    var object = $(this);
    var thing = this;
    $.ajax({
        type: 'POST',
        url: './scripts/removeSession.php',
        data: {id: thing.dataset.sid}
    }).done(function(response) {
      console.log(response);
      if (response == 'removed') {
        object.parent().parent().fadeOut();
      } else {
        console.log('error');
      }
    }).fail(function (response) {
       console.log('error');
    });
});
</script>
<?php

//session_id('2b081772c2ff0004cbea2193e3a7d1f5');
//session_start();
//session_destroy()
?>
