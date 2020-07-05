<?php
session_start();
date_default_timezone_set('Europe/London');
if ($_SESSION['loggedIn'] == null) {
  ?>
    <script>login();</script>
  <?php
  exit();
}
if (isset($debug)) {
  if ($debug == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  }
}
if ($_SESSION['loggedIn']['pending'] == 1) {
  if (isset($pending)) {
    if ($pending != 1) {
      ?>
      <script>
        newError('You do not have permission to access that page.');
        urlRoute.loadPage("Staff.Dashboard");
      </script>
      <?php
      exit();
    }
  } else {
    ?>
    <script>
      newError('You do not have permission to access that page.');
      urlRoute.loadPage("Staff.Dashboard");
    </script>
    <?php
    exit();
  }
}
if (isset($title)) {
  if ($_SESSION['loggedIn']['postedAway'] == 1) {
    if (!isset($allowPost)) {
      ?>
        <script>
        newError('You can not access that page while away.');
        urlRoute.loadPage("Staff.PostAway");
      </script>
      <?php
      exit();
    }
  }
  ?>
    <script>
      urlRoute.setPageTitle('<?php echo $title?>');
      document.title = "Key Staff -> <?php echo $title?>";
    </script>
  <?php
} else {
  ?>
    <script>
      urlRoute.setPageTitle('<?php echo $_SERVER['REQUEST_URI'];?>');
      document.title = "Key Staff -> Panel";
    </script>
  <?php
}
if (isset($dev)) {
  if ($dev == 1) {
    if ($_SESSION['loggedIn']['developer'] != '1') {
    ?>
      <script>
        newError('You do not have permission to access that page. (Development Page)');
        urlRoute.loadPage("Staff.Dashboard");
      </script>
      <?php
      exit();
    }
  }
}
if ($_SESSION['loggedIn']['permRole'] < $perm) {
  ?>
  <script>
    newError('You do not have permission to access that page.');
    urlRoute.loadPage("Staff.Dashboard");
   </script>
  <?php
  exit();
}
if (isset($media)) {
  if ($media == 1) {
    if ($_SESSION['loggedIn']['media'] != '1') {
      ?>
      <script>
        newError('You do not have permission to access that page.');
        urlRoute.loadPage("Staff.Dashboard");
      </script>
      <?php
      exit();
    }
  }
}
if (isset($social)) {
  if ($social == 1) {
    if ($_SESSION['loggedIn']['social'] != '1') {
      ?>
      <script>
        newError('You do not have permission to access that page.');
        urlRoute.loadPage("Staff.Dashboard");
      </script>
      <?php
      exit();
    }
  }
}
if (isset($radio)) {
  if ($radio == 1) {
    if ($_SESSION['loggedIn']['radio'] != '1') {
      ?>
      <script>
        newError('You do not have permission to access that page.');
        urlRoute.loadPage("Staff.Dashboard");
      </script>
      <?php
      exit();
    }
  }
}
function getThisUserSpan() {
  $db_host = "localhost";
  $db_user = "keyfm";
  $db_pass = "NxsGOH1I6Vm8tsVOAExQoiXoi17FMp";
  $db_name = "keyfm";
  $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
  $stmt->bindParam(":id", $_SESSION['loggedIn']['id']);
  $stmt->execute();
  $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($userDetails['permRole'] == 1) {
    $color = 'dstaff-text';
  }
  if ($userDetails['permRole'] == 2) {
    $color = 'sstaff-text';
  }
  if ($userDetails['permRole'] == 3) {
    $color = 'manager-text';
  }
  if ($userDetails['permRole'] == 4) {
    $color = 'admin-text';
  }

  if ($userDetails['permRole'] == 5) {
    $color = 'executive-text';
  }

  if ($userDetails['permRole'] == 6) {
    $color = 'owner-text';
  }
  $span = "<span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";
  echo $span;
}
function getUserSpan($id) {
  $db_host = "localhost";
  $db_user = "keyfm";
  $db_pass = "NxsGOH1I6Vm8tsVOAExQoiXoi17FMp";
  $db_name = "keyfm";
  $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
  $stmt->bindParam(":id", $id);
  $stmt->execute();
  $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($userDetails['permRole'] == 1) {
    $color = 'dstaff-text';
  }
  if ($userDetails['permRole'] == 2) {
    $color = 'sstaff-text';
  }
  if ($userDetails['permRole'] == 3) {
    $color = 'manager-text';
  }
  if ($userDetails['permRole'] == 4) {
    $color = 'admin-text';
  }

  if ($userDetails['permRole'] == 5) {
    $color = 'executive-text';
  }

  if ($userDetails['permRole'] == 6) {
    $color = 'owner-text';
  }
  $span = "<span class='" . $color . " userLink' onclick='loadProfile(" . $userDetails['id'] . ")'>" . $userDetails['username'] . "</span>";
  echo $span;
}
 ?>
<script>
  var x, i, j, l, ll, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
l = x.length;
for (i = 0; i < l; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  ll = selElmnt.length;
  /*for each element, create a new DIV that will act as the selected item:*/
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  /*for each element, create a new DIV that will contain the option list:*/
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < ll; j++) {
    /*for each option in the original select element,
    create a new DIV that will act as an option item:*/
    var elem = selElmnt.options[j];
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    if (elem.getAttribute("data-ico") !== null) {
      var image = document.createElement('img');
      image.setAttribute('src', elem.getAttribute("data-ico"));
      image.classList += ("selectImg");
      c.prepend(image);
    }
    
    c.addEventListener("click", function(e) {
        /*when an item is clicked, update the original select box,
        and the selected item:*/
        var y, i, k, s, h, sl, yl;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        sl = s.length;
        h = this.parentNode.previousSibling;
        for (i = 0; i < sl; i++) {
          if (this.innerHTML.includes("<img")) {
            var elemHTMLArray = this.innerHTML.split(">");
            var elemHTML = elemHTMLArray[1]
          } else {
            var elemHTML = this.innerHTML;
          }
          if (s.options[i].innerHTML == elemHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            yl = y.length;
            for (k = 0; k < yl; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      /*when the select box is clicked, close any other select boxes,
      and open/close the current select box:*/
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);
</script>