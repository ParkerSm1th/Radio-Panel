<?php
$perm = 3;
$media = 0;
$radio = 0;
$dev = 0;
$title = "Radio Spy";
include('../../includes/header.php');
include('../../includes/config.php');
$admin = false;
if ($_SESSION['loggedIn']['permRole'] >= 4) {
  $admin = true;
}
 ?>
 <style>
  .region img {
    height: 40px;
    width: 40px;
    border-radius: 200px;
  }
  .app-header .region {
    color: #fff;
    position: absolute;
    right: 25px;
    top: 12px;
    font-size: 30px;
    font-weight: 600;
  }
  .buttons .dj {
    position: absolute;
    left: 40px;
  }
  .buttons .dj i {
    font-size: 15px;
    color: #fff;
  }
  .buttons .song {
    position: absolute;
    left: 35px;
    top: 30px;
  }
  .buttons .song i {
    font-size: 15px;
    color: #fff;
  }
  .sDJ {
    color: #d4d4d4;
    margin-left: 2px;
  }
  .sDJ i {
    color: #d4d4d4;
  }
  .sSong {
    color: #d4d4d4;
    margin-left: 2px;
  }
  .sSong i {
    color: #d4d4d4;
  }
 </style>
     <div class="row">
      
     <div class="col-md-6 col-sm-12">
        <div class="application">
          <div class="app-header" style="background: #0584ff;">
            <h1 class="region"><img src="https://keyfm.net/splash/assets/images/favicon.png"></h1>
            <p class="name">KeyFM</p>
            <p class="discord">https://keyfm.net</p>
          </div>
          <div class="app-body">
            <div class="buttons">
              <div class="dj"><i class="fas fa-microphone-alt"></i> <span id="kDJ" class="sDJ"><i class="fas fa-circle-notch fa-spin"></i></span></div>
              <div class="song"><i class="fas fa-music"></i> <span id="kSong" class="sSong"><i class="fas fa-circle-notch fa-spin"></i></span></div>
            </div>
            <div class="status">
              <h1><span id="kL"><i class="fas fa-circle-notch fa-spin"></i></span> listeners</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-sm-12">
        <div class="application">
          <div class="app-header" style="background: #983885;">
            <h1 class="region"><img src="https://upbeatradio.net/staff/_assets/_avatarImages/default/default.png?3"></h1>
            <p class="name">Upbeat</p>
            <p class="discord">https://upbeatradio.net</p>
          </div>
          <div class="app-body">
            <div class="buttons">
              <div class="dj"><i class="fas fa-microphone-alt"></i> <span id="uDJ" class="sDJ"><i class="fas fa-circle-notch fa-spin"></i></span></div>
              <div class="song"><i class="fas fa-music"></i> <span id="uSong" class="sSong"><i class="fas fa-circle-notch fa-spin"></i></span></div>
            </div>
            <div class="status">
              <h1><span id="uL"><i class="fas fa-circle-notch fa-spin"></i></span> listeners</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-sm-12">
        <div class="application">
          <div class="app-header" style="background: #bc28ae;">
            <h1 class="region"><img src="https://staff.keyfm.net/images/raveLogo.png"></h1>
            <p class="name">Rave</p>
            <p class="discord">https://raveradio.net</p>
          </div>
          <div class="app-body">
            <div class="buttons">
              <div class="dj"><i class="fas fa-microphone-alt"></i> <span id="rDJ" class="sDJ"><i class="fas fa-circle-notch fa-spin"></i></span></div>
              <div class="song"><i class="fas fa-music"></i> <span id="rSong" class="sSong"><i class="fas fa-circle-notch fa-spin"></i></span></div>
            </div>
            <div class="status">
              <h1><span id="rL"><i class="fas fa-circle-notch fa-spin"></i></span> listeners</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-sm-12">
        <div class="application">
          <div class="app-header" style="background: #3498db;">
            <h1 class="region"><img src="https://itsaqua.net/logo.png"></h1>
            <p class="name">Aqua</p>
            <p class="discord">https://itsaqua.net</p>
          </div>
          <div class="app-body">
            <div class="buttons">
              <div class="dj"><i class="fas fa-microphone-alt"></i> <span id="aDJ" class="sDJ"><i class="fas fa-circle-notch fa-spin"></i></span></div>
              <div class="song"><i class="fas fa-music"></i> <span id="aSong" class="sSong"><i class="fas fa-circle-notch fa-spin"></i></span></div>
            </div>
            <div class="status">
              <h1><span id="aL"><i class="fas fa-circle-notch fa-spin"></i></span> listeners</h1>
            </div>
          </div>
        </div>
      </div>

     </div>


 <script>
  clearInterval(pageInt); 
  pageInt = null;
  var pageInt = setInterval(updateStats, 10000);
  function xmlToJson(xml) {
    // Create the return object
    var obj = {};

    if (xml.nodeType == 1) { // element
      // do attributes
      if (xml.attributes.length > 0) {
      obj["@attributes"] = {};
        for (var j = 0; j < xml.attributes.length; j++) {
          var attribute = xml.attributes.item(j);
          obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
        }
      }
    } else if (xml.nodeType == 3) { // text
      obj = xml.nodeValue;
    }

    // do children
    if (xml.hasChildNodes()) {
      for(var i = 0; i < xml.childNodes.length; i++) {
        var item = xml.childNodes.item(i);
        var nodeName = item.nodeName;
        if (typeof(obj[nodeName]) == "undefined") {
          obj[nodeName] = xmlToJson(item);
        } else {
          if (typeof(obj[nodeName].push) == "undefined") {
            var old = obj[nodeName];
            obj[nodeName] = [];
            obj[nodeName].push(old);
          }
          obj[nodeName].push(xmlToJson(item));
        }
      }
    }
    return obj;
  };
  function updateStats() {
      $.get("https://api.livida.net/api/radio/keyfm", function(data, status) {
        $("#kL").html(data.data.listeners);
        $("#kDJ").html(data.data.dj);
        $("#kSong").html(data.data.song.name);
      })
      .catch(function(err) {
        $("#kL").html('<i class="fal fa-exclamation-circle"></i>');
        $("#kDJ").html('<i class="fal fa-exclamation-circle"></i>');
        $("#kSong").html('<i class="fal fa-exclamation-circle"></i>');
      });
      $.get("https://api.livida.net/api/radio/aqua", function(data, status) {
        $("#aL").html(data.data.listeners);
        $("#aDJ").html(data.data.dj);
        $("#aSong").html(data.data.song.name);
      })
      .catch(function(err) {
        $("#aL").html('<i class="fal fa-exclamation-circle"></i>');
        $("#aDJ").html('<i class="fal fa-exclamation-circle"></i>');
        $("#aSong").html('<i class="fal fa-exclamation-circle"></i>');
      });
      $.get("https://api.livida.net/api/radio/rave", function(data, status) {
        $("#rL").html(data.data.listeners);
        $("#rDJ").html(data.data.dj);
        $("#rSong").html(data.data.song.name);
      })
      .catch(function(err) {
        $("#rL").html('<i class="fal fa-exclamation-circle"></i>');
        $("#rDJ").html('<i class="fal fa-exclamation-circle"></i>');
        $("#rSong").html('<i class="fal fa-exclamation-circle"></i>');
      });
      $.get("http://stats.upbeat.pw/", function(data, status) {
        var xml = xmlToJson(data);
        $("#uL").html(xml.SHOUTCASTSERVER.CURRENTLISTENERS["#text"]);
      });
      $.post("https://upbeatradio.net/v3/_scripts_/ajax.php",
      {
        request: "refreshStats"
      }, function(data, status) {
        $("#uDJ").html(data.dj);
        $("#uSong").html(data.song);
      });
      
  }
  updateStats();
 </script>
