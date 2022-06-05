function hideTrainer() {
  localStorage.removeItem("mc2Trainer");
  document.getElementById("hideTrainerButton").style.visibility = "hidden";
  document.getElementById("showTrainerButton").style.visibility = "visible";
}

function showTrainer() {
  localStorage.setItem("mc2Trainer", "true");
  document.getElementById("hideTrainerButton").style.visibility = "visible";
  document.getElementById("showTrainerButton").style.visibility = "hidden";
}

function selectButton() {
  

  if (localStorage.getItem("mc2Trainer")) {
    document.getElementById("hideTrainerButton").style.visibility = "visible";
    document.getElementById("showTrainerButton").style.visibility = "hidden";
  } else {

    document.getElementById("hideTrainerButton").style.visibility = "hidden";
    document.getElementById("showTrainerButton").style.visibility = "visible";
  }
}
