var xhttp = new XMLHttpRequest();

function sendData(data, then, url) {
  xhttp.open("POST", url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = function() {
    if(xhttp.readyState == 4 && xhttp.status == 200) {
      then(JSON.parse(xhttp.responseText));
    }
  }
  xhttp.send(data);
}
