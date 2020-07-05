<?php
$perm = 1;
$media = 0;
$radio = 0;
$dev = 1;
$debug = true;
$title = "Manage Pages";
include('../../includes/header.php');
include('../../includes/config.php');
 ?>
<style>
.smallListTitle {
  font-size: 20px;
}
.over {
  border: 2px dashed #000;
}
</style>
    <div class="card-actions">
      <a href="Dev.NewPage" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">New Page</button>
      </a>
    </div>

    <?php

      $stmt = $conn->prepare("SELECT * FROM nav_ranks ORDER BY permRole");
      $stmt->execute();

      foreach($stmt as $row) {
        $id = $row['id'];
        ?>
    <div class="card usersCard m-b-10">
      <div class="card-body smallListCard <?php echo $row['class']?>-background">
        <a data-toggle="collapse" href="#sf-<?php echo $id?>" aria-expanded="false" class="cShow" aria-controls="sf-<?php echo $id?>">
          <h1 class="card-title usersTitle smallListTitle"><i class="<?php echo $row['icon']?>"></i> <?php echo $row['name']?></h1>
        </a>
        <div class="collapse autoClose" style="padding: 0px 0 !important;" id="sf-<?php echo $id?>">
          <div class="table-responsive">
            <table class="table usersTable">
              <thead>
                <tr>
                  <th>
                    Name
                  </th>
                  <th>
                    URL
                  </th>
                  <th>
                    Source
                  </th>
                  <th>
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $stmt = $conn->prepare("SELECT * FROM panel_pages WHERE nav_rank = :rank ORDER BY position");
                  $stmt->bindParam(":rank", $id);
                  $stmt->execute();

                  foreach($stmt as $data) {
                    $source = explode(".", $data['url']);
                    if ($data['dev'] == "1") {
                      $icon = "fa-eye-slash";
                      $iText = "Development";
                    } else {
                      $icon = "fa-eye";
                      $iText = "Production";
                    }
                    ?>
                      <tr data-id="<?php echo $data['id']?>">
                        <td>
                          <?php echo $data['name'] ?>
                          <div class="tableButton devMode" data-id="<?php echo $data['id']?>">
                            <span class="cTooltip"><i class="fas <?php echo $icon?>"></i><b title="<?php echo $iText?>"></b></span>
                          </div>
                        </td>
                        <td>
                          <?php echo $data['url'] ?>
                        </td>
                        <td>
                          <?php echo "/Pages/" . $source[0] . "/" . $source[1] . ".php"?>
                        </td>
                        <td>
                          <div class="tableButton moveUp" data-id="<?php echo $data['id']?>">
                            <span class="cTooltip"><i class="fas fa-arrow-up"></i><b title="Move Up"></b></span>
                          </div>
                          <div class="tableButton moveDown" data-id="<?php echo $data['id']?>">
                            <span class="cTooltip"><i class="fas fa-arrow-down"></i><b title="Move Down"></b></span>
                          </div>
                          <div class="tableButton delete" data-id="<?php echo $data['id']?>">
                            <span class="cTooltip"><i class="fas fa-trash"></i><b title="Delete"></b></span>
                          </div>
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
$(".moveUp").click(function(e) {
  var elem = $(this);
  var otherId = elem.parent().parent().prev('tr').attr('data-id');
  if (otherId == undefined) {
    otherId = "";
  }
  $.ajax({
      type: 'GET',
      url: 'scripts/managePagePosition.php?page1=' + $(this).attr('data-id') + '&page2=' + otherId
  }).done(function(response) {
    console.log(response);
    if (response == "swapped") {
      elem.parent().parent().insertBefore(elem.parent().parent().prev());
      newSuccess("You have moved that page up..");
      refreshNav();
    } else if (response == "missing") {
      newError("You can't move that page up any further");
    } else {
      newError("An unknown error occured.");
    }
  });
});
$(".moveDown").click(function(e) {
  var elem = $(this);
  var otherId = elem.parent().parent().next('tr').attr('data-id');
  if (otherId == undefined) {
    otherId = "";
  }
  $.ajax({
      type: 'GET',
      url: 'scripts/managePagePosition.php?page1=' + $(this).attr('data-id') + '&page2=' + otherId
  }).done(function(response) {
    console.log(response);
    if (response == "swapped") {
      elem.parent().parent().insertAfter(elem.parent().parent().next());
      newSuccess("You have moved that page down..");
      refreshNav();
    } else if (response == "missing") {
      newError("You can't move that page down any further");
    } else {
      newError("An unknown error occured.");
    }
  });
});
$(".devMode").click(function(e) {
  var elem = $(this);
  $.ajax({
      type: 'GET',
      url: 'scripts/managePage.php?action=dev&page=' + $(this).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "prod") {
      elem.html(`
        <span class="cTooltip"><i class="fas fa-eye"></i><b title="Production"></b></span>
      `);
      newSuccess("That page has been enabled for production.");
      refreshNav();
    } else if (response == "dev") {
      elem.html(`
        <span class="cTooltip"><i class="fas fa-eye-slash"></i><b title="Development"></b></span>
      `);
      newSuccess("That page has been disabled for production.");
      refreshNav();
    } else {
      newError("An unknown error occured.");
    }
  });
});
$(".delete").click(function(e) {
  var elem = $(this);
  $.ajax({
      type: 'GET',
      url: 'scripts/managePage.php?action=delete&page=' + $(this).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "deleted") {
      elem.parent().parent().fadeOut();
      newSuccess("You have deleted that page.");
      refreshNav();
    } else {
      newError("An unknown error occured.");
    }
  });
});
</script>
