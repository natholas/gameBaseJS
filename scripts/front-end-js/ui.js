var menuNames = ["menu", "login", "signup", "reset", "change"];

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
