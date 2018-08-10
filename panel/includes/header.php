<?php
session_start();
if ($_SESSION['loggedIn']['username'] == null) {
  ?>
    <script>login();</script>
  <?php
  exit();
}
if ($_SESSION['loggedIn']['permRole'] < $perm) {
  ?>
  <script>
    newError('You do not have permission to access that page.');
    urlRoute.loadPage("Staff.Dashboard");
   </script>
  <?php
}
if ($dev == 1) {
  if ($_SESSION['loggedIn']['developer'] != '1') {
  ?>
    <script>
      newError('You do not have permission to access that page.');
      urlRoute.loadPage("Staff.Dashboard");
     </script>
    <?php
  }
}
if ($media == 1) {
  if ($_SESSION['loggedIn']['media'] != '1') {
    ?>
    <script>
      newError('You do not have permission to access that page.');
      urlRoute.loadPage("Staff.Dashboard");
     </script>
    <?php
  }
}
if ($radio == 1) {
  if ($_SESSION['loggedIn']['radio'] != '1') {
    ?>
    <script>
      newError('You do not have permission to access that page.');
      urlRoute.loadPage("Staff.Dashboard");
     </script>
    <?php
  }
}
 ?>
