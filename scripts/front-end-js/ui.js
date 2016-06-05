var menuNames = ["menu", "login", "signup", "reset", "change"];
var errorTimeout = null;
var messageTimeout = null;

function inputCheck(e) {
  if (e.target.value.length > 0) {
    e.target.className = "open";
  } else {
    e.target.className = "";
  }
}

function showMenu(show) {
    for (var i=0;i<menuNames.length;i++) {
        find("#" + menuNames[i] + "-screen").className = "screen";
    }
    find("#" + show + "-screen").className = "screen visible";
    var menuName = capitalize(show);

    document.title = menuName + " - Base Game";
    return false;
}

function startLoading() {
    find("#loading-screen").className = "screen visible";
}

function endLoading() {
    find("#loading-screen").className = "screen";
}

function showMessage(type, message, time) {
    if (!time) {
        time = 3000;
    }
    if (type == "error") {
        clearTimeout(errorTimeout);
        errorTimeout = null;
        find(".error").innerHTML = message;
        find(".error").className = "error visible";
        errorTimeout = setTimeout(function() {
            hideMessage("error");
        }, time);
    } else {
        clearTimeout(messageTimeout);
        messageTimeout = null;
        find(".message").innerHTML = message;
        find(".message").className = "message visible";
        errorTimeout = setTimeout(function() {
            hideMessage("message");
        }, time);
    }
}

function hideMessage(type) {
    if (type == "error") {
        clearTimeout(errorTimeout);
        errorTimeout = null;
        find(".error").className = "error";
    } else {
        clearTimeout(messageTimeout);
        messageTimeout = null;
        find(".message").className = "message";
    }
}
