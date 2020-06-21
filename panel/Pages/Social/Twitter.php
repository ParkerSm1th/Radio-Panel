<?php
$perm = 1;
$media = 0;
$radio = 0;
$social = 1;
$dev = 1;
$debug = 1;
$title = "Twitter";
include('../../includes/header.php');
include('../../includes/config.php');
$id = $_SESSION['loggedIn']['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$current = $row['djSays'];
 ?>
<div class="card" style="margin-bottom: 20px;">
  <div class="card-head">
    <h1>New Tweet</h1>
  </div>
  <div class="card-body">
    <div class="alert alert-warning text-center" role="alert">
         Please remember to keep your tweet appropriate and make sure that it follows the <a href="Radio.Rules" class="web-page text-white">media rules</a>
    </div>
    <form class="forms-sample" id='newTweet' action="#">
      <div class="form-group" id='errorFieldOut' style='display: none;'>
        <span class="btn btn-danger submit-btn btn-block" id='error'></span>
      </div>
      <div class="form-group" id='discordMsgOut' style='display: none;'>
        <p class="btn btn-success submit-btn btn-block" id='success'></p>
      </div>
      <div class="form-group">
        <label for="value">Tweet Content</label>
        <textarea name="content" id="tweetC" type="text" rows="7" class="form-control" placeholder="Enter Tweet Here"></textarea>
      </div>
      <div class="form-group">
        <button class="btn btn-success mr-2" id='submit'>Tweet</button>
      </div>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-head">
    <h1>Your Past Tweets</h1>
  </div>
  <div class="card-body">
    <style>
      .tweet {
        background: #0e1b2d;
        height: auto;
        border-radius: 5px;
        margin-bottom: 15px;
      }
      .tweetheader {
        height: 60px;
        position: relative;
      }
      .tweetHeader img {
        height: 45px;
        border-radius: 100%;
        position: absolute;
        left: 20px;
        top: 10px;
      }
      .tweetHeader .data {
        position: absolute;
        left: 70px;
        top: 10px;
        color: #ffffffbd;
      }
      .tweetHeader .data span {
        font-size: 18px;
      }
      .tweetContent {
        height: auto;
        color: #fff;
        padding: 10px;
        padding-left: 70px;
        margin-top: -36px;
        font-size: 16px;
      }
    </style>
    <div id="tweets">
      <?php
        $stmt = $conn->prepare("SELECT * FROM tweets WHERE user = :id AND deleted = 0 ORDER BY id DESC");
        $stmt->bindParam(':id', $_SESSION['loggedIn']['id']);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count == 0) {
          ?>
            <div class="text-center" id="noTweets">
            <h2 style="margin: auto;
            color: #ffffffe8;
            padding-top: 10px;
            text-align: center;">You do not have any tweeting history.. 🤔</h2>
            </div>
          <?php
        } else {
          foreach($stmt as $row) {
          ?>
            <div class="tweet">
              <div class="tweetHeader">
                <img src="../images/square.png">
                <p class="data"><?php getUserSpan($row['user']) ?> @KeyFMRadio &bull; <?php echo $row['times']?></p>
              </div>
              <div class="tweetContent">
              
                  <?php echo $row['content'] ?>
              </div>
            </div>
          <?php
          }
        }
      ?>
    </div>
  </div>
</div>
<script>
$(function() {

  var form = $('#newTweet');
  $(form).submit(function(event) {
      var error = false;
      var errorMessage = '';
      event.preventDefault();
      console.log("Submitted");
      var formData = $(form).serialize();
      var content = $('#tweetC');
      var contentVal = $('#tweetC').val();

      if (content.val() == null || content.val() == '') {
        console.log("EMPTY");
        error = true;
        errorMessage = "You can't send an empty tweet!";
      }

      if (error) {
        console.log("ERROR");
        $('#errorFieldOut').fadeIn();
        $('#error').removeClass('btn-success');
        $('#error').addClass('btn-danger');
        $('#error').html(errorMessage);
        return true;
      }
      $.ajax({
          type: 'POST',
          url: 'scripts/newTweet.php',
          data: formData
      }).done(function(response) {
        console.log(response);
        if (response == 'tweeted') {
          $('#errorFieldOut').fadeIn();
          $('#error').removeClass('btn-danger');
          $('#error').addClass('btn-success');
          $('#error').html('Success! Your tweet has been sent!');
          $("#noTweets").fadeOut();
          var tweetContent = $('#tweetC').val();
          $('#tweetC').val('');
          setTimeout(function() {
            $("#errorFieldOut").fadeOut();
          }, 5000);
          <?php 
            $newTweetID = "t" . rand(0, 100);
          ?>
          $("#tweets").prepend(`
            <div class="tweet" id="<?php echo $newTweetID?>" style="display: none;">
                <div class="tweetHeader">
                  <img src="../images/square.png">
                  <p class="data"><?php getThisUserSpan() ?> @KeyFMRadio &bull; <?php echo date('Y-d-m H:i:s')?></p>
                </div>
                <div class="tweetContent">
                  ${tweetContent.replace(/(?:\r\n|\r|\n)/g, '<br>')}<br><br>- <?php echo $_SESSION['loggedIn']['username']; ?>
                </div>
              </div>
          `);
          $("#<?php echo $newTweetID ?>").fadeIn();
        } else {
          $('#error').addClass('btn-danger');
          $('#error').removeClass('btn-success');
          $('#errorFieldOut').fadeIn();
          $('#error').html('Unknown error occured..');
        }
      }).fail(function (response) {
          $('#errorFieldOut').fadeIn();
          $('#error').html('Unknown error occured.');
      });
    });
});

</script>
