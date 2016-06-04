function get_info(x) {
    data = {};
    sendData(data, handle_get_info, "/api/get_info.php");
}
get_info();

function handle_get_info(data) {
    console.log(data);
    if (data.status == "success") {
        showMenu("menu");
    } else {
        showMenu("login");
    }
}

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
    if (data.status == "success") {
        get_info();
    } else {
        alert("Wrong username or password");
    }
}

function signup() {
    var username = find("#signup-username").value;
    var email = find("#signup-email").value;
    var password = find("#signup-password").value;
    var passwordrepeat = find("#signup-passwordrepeat").value;

    if (password == passwordrepeat && username.length > 3 && password.length > 3) {
        var data = {
          "username": username,
          "password": password,
          "email": email
        }
        sendData(data, handle_signup, "/api/signup.php");
    } else if (password == passwordrepeat) {
        alert("username or password too short");
    } else {
        alert("Passwords don't match");
    }

    return false;
}

function handle_signup(data) {
    if (data.status == "success") {
        get_info();
    } else {
        alert("Username already exist");
    }
}

function logout() {
    var data = {};
    sendData(data, get_info, "/api/logout.php");
}
