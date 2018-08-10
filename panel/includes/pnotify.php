<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/pnotify.custom.js"></script>
<script type="text/javascript">
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
