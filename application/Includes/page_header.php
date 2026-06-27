<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/helpers.php';

use RadioPanel\Core\Auth;
use RadioPanel\Core\Security;

if (!Auth::check()) {
    ?>
    <script>login();</script>
    <?php
    exit;
}

$user = Auth::user();
$minPerm = isset($perm) ? (int) $perm : 1;

Auth::requireAccess($minPerm, [
    'dev' => !empty($dev),
    'media' => !empty($media),
    'social' => !empty($social),
    'radio' => !empty($radio),
    'pending' => !empty($pending),
    'allowPost' => !empty($allowPost),
]);

if (isset($title)) {
    $safeTitle = Security::escape($title);
    ?>
    <script>
      urlRoute.setPageTitle(<?php echo Security::escapeJs($title); ?>);
      document.title = "Key Staff -> <?php echo $safeTitle; ?>";
    </script>
    <?php
} else {
    ?>
    <script>
      urlRoute.setPageTitle(<?php echo Security::escapeJs($_SERVER['REQUEST_URI'] ?? ''); ?>);
      document.title = "Key Staff -> Panel";
    </script>
    <?php
}

function getThisUserSpan()
{
    Auth::renderThisUserSpan();
}

function getUserSpan($id)
{
    Auth::renderUserSpan((int) $id);
}

function returnUserSpan($id)
{
    return Auth::returnUserSpan((int) $id);
}

?>
<script>
  var x, i, j, l, ll, selElmnt, a, b, c;
x = document.getElementsByClassName("custom-select");
l = x.length;
for (i = 0; i < l; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  if (!selElmnt) {
    continue;
  }
  ll = selElmnt.length;
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < ll; j++) {
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
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
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
    if (arrNo.indexOf(i) === -1) {
      x[i].classList.add("select-hide");
    }
  }
}
document.addEventListener("click", closeAllSelect);
</script>
