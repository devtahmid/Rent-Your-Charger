function verifyTime() {

  //Get the start time and parse it as a Date object
  var startTimeString = document.getElementById("startTime").value;
  var [hours, minutes] = startTimeString.split(":");
  var startTimeObj = new Date();
  startTimeObj.setHours(hours);
  startTimeObj.setMinutes(minutes);
  startTimeObj.setSeconds(0);

  var endTimeObj = new Date(startTimeObj.getTime() + (3 * 60 * 60 * 1000));
  var endTimeString = endTimeObj.toTimeString().slice(0, 5);

  document.getElementById("endTime").setAttribute("max", endTimeString);
  console.log(endTimeString);
}

// prevent the form from submitting if conditons are not met

document.getElementById("myForm").addEventListener("submit", function (event) {
  //get end time max value and parse it as a date object
  var endTimeMaxString = document.getElementById("endTime").getAttribute("max");
  var [hours, minutes] = endTimeMaxString.split(":");
  var endTimeMaxObj = new Date();
  endTimeMaxObj.setHours(hours);
  endTimeMaxObj.setMinutes(minutes);
  endTimeMaxObj.setSeconds(0);

  //get the end time and parse it as  adate object
  var endTimeString = document.getElementById("endTime").value;
  var [hours, minutes] = endTimeString.split(":");
  var endTimeObj = new Date();
  endTimeObj.setHours(hours);
  endTimeObj.setMinutes(minutes);
  endTimeObj.setSeconds(0);


  // check if end time is more than the max
  if (endTimeObj > endTimeMaxObj) {
    alert("End Time must be less than 3 hours from start time.");
    event.preventDefault();
    return;
  }

})

function setMinTime() {
  // Get the value of the date picker
  const datePickerValue = document.getElementById("date").value;

  // Get the current date
  const today = new Date().toISOString().slice(0, 10);
  console.log(today);
  console.log("terst");
  // If the date picker's value is today, set the min time to the current time
  if (datePickerValue === today) {
    const now = new Date();
    now.setMinutes(now.getMinutes() + 5);
    const minTime = now.toTimeString().slice(0, 5);
    document.getElementById("startTime").setAttribute("min", minTime);
  }
  // Otherwise, allow any time to be selected
  else
    document.getElementById("startTime").removeAttribute("min");

}