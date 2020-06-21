<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Short URLs";
include('../../includes/header.php');
include('../../includes/config.php');

 ?>
    <div class="card-actions">
      <a href="Manager.NewShort" class="web-page">
        <button class="profile-close-button btn btn-light mr-2">New Short URL</button>
      </a>
    </div>
    <div class="table-responsive">
      <table class="table usersTable">
        <thead>
          <tr>
            <th>
              Short URL
            </th>
            <th>
              Hits
            </th>
            <th>
              Actions
            </th>
            <th>
              Full URL
            </th>
          </tr>
        </thead>
        <tbody>
          <?php
            $stmt = $conn->prepare("SELECT * FROM redirect ORDER BY id");
            $stmt->execute();

            foreach($stmt as $row) {
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $row['slug']; ?>
                </td>
                <td>
                  <?php echo $row['hits'] ?>
                </td>
                <td>
                  <div class="tableButton">
                    <span class="cTooltip"><i data-id="<?php echo $row['id']?>" class="editShort fas fa-pencil"></i><b title="Edit Short"></b></span>
                  </div>
                  <div class="tableButton">
                    <span class="cTooltip"><i data-id="<?php echo $row['id']?>" class="deleteShort fas fa-trash"></i><b title="Delete Short"></b></span>
                  </div>
                </td>
                <td>
                  <?php echo $row['url'] ?>
                </td>
              </tr>
              <?php
            }

            ?>
            <tr>
              <td class="py-1">
                @
              </td>
              <td>
                N/A
              </td>
              <td>
                N/A
              </td>
              <td>
                https://twitter.com/KeyFMRadio
              </td>
            </tr>
        </tbody>
      </table>
    </div>

<script>
$(".deleteShort").on("click", function () {
  $(this).parent().parent().parent().parent().fadeOut();
  $.ajax({
      type: 'POST',
      url: 'scripts/deleteShort.php?id=' + $(this).attr('data-id')
  }).done(function(response) {
    console.log(response);
    if (response == "deleted") {
      $(this).parent().parent().parent().parent().fadeOut();
      newSuccess("You have deleted that Short URL!");
    } else {
      newError("An unknown error occured.");
    }
  });
});
</script>
