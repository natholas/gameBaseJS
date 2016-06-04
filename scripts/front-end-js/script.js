function inputCheck(e) {
  if (e.target.value.length > 0) {
    e.target.className = "open";
  } else {
    e.target.className = "";
  }
}
