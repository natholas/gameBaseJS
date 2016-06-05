function get_info() {
    data = {};
    sendData(data, handle_get_info, "/api/get_info.php");
}

function handle_get_info(data) {
    if (data.status == "success") {
        showMenu("menu");
        find("#player_name").innerHTML = data.user_name;
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
} else {
    showMessage("error", "Wrong username or password");
}

  return false;
}

function handle_login(data) {
    if (data.status == "success") {
        get_info();
        showMessage("message", "Logged in");
    } else {
        showMessage("error", "Wrong username or password");
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
        showMessage("error", "Username and password must be at least 4 characters long", 4000);
    } else {
        showMessage("error", "The passwords don't match");
    }

    return false;
}

function handle_signup(data) {
    if (data.status == "success") {
        get_info();
        showMessage("message", "Account created");
    } else {
        showMessage("error", "An account already exists with that username or email address", 5000);
    }
}

function logout() {
    var data = {};
    sendData(data, process_logout, "/api/logout.php");
    return false;
}

function process_logout(data) {
    showMessage("message", "Logged out");
    get_info();
}

function reset_email() {

    var emailaddress = find("#reset-email").value;

    if (emailaddress.length > 3) {
        var data = {
            "email": emailaddress
        }

        sendData(data, handle_reset_email, "/api/reset_password.php");

    } else {
        showMessage("error", "Invalid email address");
    }

    return false;
}

function handle_reset_email(data) {
    console.log(data);
    if (data.status == "success") {
        showMenu("change");
        showMessage("message", "We have sent a code to your email address. Please enter it below", 5000);
    } else {
        showMessage("error", "No account exists with that email address", 4000);
    }
}

function change_password() {

    var code = find("#change-code").value;
    var newpassword = find("#change-newpassword").value;
    var newpasswordrepeat = find("#change-newpasswordrepeat").value;

    if (code.length == 8 && newpassword == newpasswordrepeat) {

        data = {
            "code": code,
            "new_password": newpassword
        }
        sendData(data, handle_change_password, "/api/reset_password.php");

    } else {
        showMessage("error", "The passwords that you entered don't match");
    }

    return false;

}

function handle_change_password(data) {
    if (data.status == "success") {
        showMenu("login");
        showMessage("message", "Password changed. Please login");
    } else {
        showMessage("error", "The code you entered is not correct");
    }
}
