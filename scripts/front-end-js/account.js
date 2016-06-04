function login() {
  var username = find("#login-username").value;
  var password = find("#login-password").value;

  if (username.length > 3 && password.length > 3) {
    var data = {
      "username": username,
      "password": password
    }
    sendData(data, handle_login, "/api/login.php");
  }

  return false;
}

function handle_login(data) {
  console.log(data);
}
