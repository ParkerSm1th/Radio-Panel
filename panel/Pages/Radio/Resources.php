<?php
  $perm = 1;
  $media = 0;
  $radio = 1;
  $dev = 0;
  $title = "Radio Resources";
  include('../../includes/header.php');
  include('../../includes/config.php');
 ?>
 <style>
.card {
  margin-top: 10px;
}
.updated {
  margin-top: -7px;
    margin-bottom: 15px;
}
 </style>
<h1 class="card-title text-center">Radio Imaging</h1>
<div class="row">
 <div class="col-md-6 col-sm-12 stretch-card">
   <div class="card">
     <div class="card-body text-center">
       <h2 style="margin-bottom: 10px" class="card-title">Backing Tracks</h2>
        <p class="text-muted updated">Last Updated: 9/6/20</p>
        <button style="width: 100%" data-file="BEDs" class="download btn btn-info">Download</button>

     </div>
   </div>
 </div>
 <div class="col-md-6 col-sm-12 stretch-card">
   <div class="card">
     <div class="card-body text-center">
       <h2 style="margin-bottom: 10px" class="card-title">Jingles</h2>
       <p class="text-muted updated">Last Updated: 9/6/20</p>
       <button style="width: 100%" data-file="Jingles" class="download btn btn-info">Download</button>
     </div>
   </div>
 </div>
 <div class="col-md-4 col-sm-12 stretch-card">
   <div class="card">
     <div class="card-body text-center">
       <h2 style="margin-bottom: 10px" class="card-title">Power Intros</h2>
      <p class="text-muted updated">Last Updated: 9/6/20</p>
      <button style="width: 100%" data-file="Intros" class="download btn btn-info">Download</button>

     </div>
   </div>
 </div>
 <div class="col-md-4 col-sm-12 stretch-card">
   <div  class="card">
     <div class="card-body text-center">
       <h2 style="margin-bottom: 10px" class="card-title">Stabs</h2>
       <p class="text-muted updated">Last Updated: 9/6/20</p>
       <button style="width: 100%" data-file="Stabs" class="download btn btn-info">Download</button>
     </div>
   </div>
 </div>
 <div class="col-md-4 col-sm-12 stretch-card">
   <div  class="card">
     <div class="card-body text-center">
       <h2 style="margin-bottom: 10px" class="card-title">Sweepers</h2>
      <p class="text-muted updated">Last Updated: 9/6/20</p>
      <button style="width: 100%" data-file="Sweepers" class="download btn btn-info">Download</button>

     </div>
   </div>
 </div>
</div>
<h1 class="card-title text-center" style="margin-top: 20px;">Software</h1>
<div class="row">
  <div class="col-md-4 col-sm-12 stretch-card">
    <div class="card">
      <div class="card-body text-center">
        <h2 style="margin-bottom: 10px" class="card-title">SAM BC & Firebird</h2>
        <p class="text-muted updated">For: Windows</p>
        <button style="width: 100%" data-file="KeyFM-SAM" class="download btn btn-info">Download</button>
        </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-12 stretch-card">
    <div class="card">
      <div class="card-body text-center">
        <h2 style="margin-bottom: 10px" class="card-title">Audio Hijack</h2>
        <p class="text-muted updated">For: Mac</p>
        <button style="width: 100%" data-file="KeyFM-AudioHijack" class="download btn btn-info">Download</button>
        </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-12 stretch-card">
    <div class="card">
      <div class="card-body text-center">
        <h2 style="margin-bottom: 10px" class="card-title">Nicecast <span style="font-size: 15px; font-weight: 600;">(outdated)</span></h2>
        <p class="text-muted updated">For: Mac</p>
        <button style="width: 100%" data-file="KeyFM-Nicecast" class="download btn btn-info">Download</button>
        </div>
    </div>
  </div>
 </div>
 <script>
  $(".download").click(function() {
    var name = $(this).attr("data-file");
    console.log(name);
    ajax_download("https://staff.keyfm.net/resources/" + name + ".zip");
    var name = null;
  });
  function ajax_download(url, data) {
    $("#download_iframe").remove();
    var $iframe,
        iframe_doc,
        iframe_html, 
        input_name;

    if (($iframe = $('#download_iframe')).length === 0) {
        $iframe = $("<iframe id='download_iframe'" +
                " style='display: none' src='about:blank'></iframe>"
               ).appendTo("body");
    }

    iframe_doc = $iframe[0].contentWindow || $iframe[0].contentDocument;

    if (iframe_doc.document) {
        iframe_doc = iframe_doc.document;
    }

    iframe_html = "<html><head></head><body><form method='POST' action='" +
                  url +"'>" +
                  "<input type=hidden name='" + input_name + "' value='" +
                  JSON.stringify(data) +"'/></form>" +
                  "</body></html>";

    iframe_doc.open();
    iframe_doc.write(iframe_html);
    $(iframe_doc).find('form').submit();
}
 </script>