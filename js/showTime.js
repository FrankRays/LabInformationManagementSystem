function displaydatetime() {
if (!document.layers && !document.all)	return;
  var today;
  var timeLocal;
  today = new Date();
  timeLocal = today.toLocaleString(); //Convert to current locale.
  if (document.layers) {
    document.layers.clockLocal.document.write(timeLocal);
    document.layers.clockLocal.document.close();
    }
  else if (document.all) {
  clockLocal.innerHTML = timeLocal;
  }
  setTimeout("displaydatetime()", 500);
}
  window.onload = displaydatetime;