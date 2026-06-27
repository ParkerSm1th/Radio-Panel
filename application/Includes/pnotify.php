<?php
 ?>
 <script>
 function newError(text) {
   new PNotify({
     title: 'Error',
     text: text,
     type: 'error'
   });
   return "sent";
 }
 function newSuccess(text) {
   new PNotify({
     title: 'Success',
     text: text,
     type: 'success'
   });
   return "sent";
 }
 function newWarn(text) {
   new PNotify({
     title: 'Success',
     text: text,
     type: 'success'
   });
   return "sent";
 }
 </script>
