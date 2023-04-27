var presentLatitude = 26.093364; //center of bahrain
var presentLongitude = 50.539511; //center of bahrain
var radius = 20; // the distance between center of the map to the corner of the map


if (!navigator.geolocation) { //check to see if geolocation suppported
  alert('Geolocation is not supported');
} else {
  document.getElementsByName('latitude')[0].value = 'Locating..';
  document.getElementsByName('longitude')[0].value = 'Locating..';
  //function to get the current position
  navigator.geolocation.getCurrentPosition(positionSucess, positionError);
}

//display the map

//from term22023.html file
var mymap = L.map('map').setView([presentLatitude, presentLongitude], 10);
//adding map layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
  maxZoom: 18
}).addTo(mymap);
//Creating scale control - https://www.tutorialspoint.com/leafletjs/leafletjs_controls.htm
L.control.scale().addTo(mymap);
updateRadius(); // to display the chargepoints on the first view of the map

//event will be triggered when map zoom changes or the map is moved
mymap.addEventListener('moveend', function() {
  updateRadius();
});

// function to update the radius
function updateRadius(event) {
  // Get the map edges
  var bounds = mymap.getBounds();
  //get map center
  const mapCenter = mymap.getCenter();

  // Calculate the distance from the center to the edge of the map
  coordinateDifference = mapCenter.distanceTo(bounds.getNorthWest());
  /* console.log("distance to edge:" + radius);
  console.log(mymap.getBounds().getNorthWest());
  console.log(mymap.getCenter()); */
  radius = haversineDistance(bounds.getNorthWest().lat, bounds.getNorthWest().lng, mapCenter.lat, mapCenter.lng);
  //console.log("radius-" + radius);

  //if coordinates is selected, update the r in the form
  if (document.getElementById('coordinates').checked)
    document.getElementsByName('distance')[0].value = radius;

  requestMarkersAjax();
}

//function to return the distance between two points in km
function haversineDistance(lat1, lng1, lat2, lng2) {
  const earthRadius = 6371000; // in meters

  const deltaLat = toRadians(lat2 - lat1);
  const deltaLng = toRadians(lng2 - lng1);

  const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
    Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
    Math.sin(deltaLng / 2) * Math.sin(deltaLng / 2);

  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var distance = earthRadius * c;

  distance = distance / 1000; //convert meter to km
  return distance.toFixed(2); //round to 2 decimal places  https://stackoverflow.com/questions/11832914/how-to-round-to-at-most-2-decimal-places-if-necessary

}

function toRadians(degrees) {
  return degrees * Math.PI / 180;
}

//to handle toggling of SearchType radio buttons
function changeForm() {
  if (document.getElementById('address').checked) {
    document.getElementsByName('distanceCheckbox')[0].disabled = true;
    document.getElementsByName('distance')[0].value = "";
    document.getElementsByName('distance')[0].disabled = true;
    document.getElementsByName('distance')[0].required = false;
    document.getElementsByName('latitude')[0].disabled = true;
    document.getElementsByName('latitude')[0].value = "";
    document.getElementsByName('longitude')[0].disabled = true;
    document.getElementsByName('longitude')[0].value = "";
    document.getElementsByName('streetAddress')[0].disabled = false;
  } else if (document.getElementById('coordinates').checked) { //if coordinates checkbox is selected
    document.getElementsByName('distanceCheckbox')[0].disabled = false;
    document.getElementsByName('distance')[0].disabled = false;
    document.getElementsByName('distance')[0].required = true;
    document.getElementsByName('latitude')[0].disabled = false;
    document.getElementsByName('longitude')[0].disabled = false;
    document.getElementsByName('latitude')[0].disabled = false;
    document.getElementsByName('streetAddress')[0].disabled = true;
    document.getElementsByName('streetAddress')[0].value = "";

  }
}

// used in navigator.geolocation.getCurrentPosition(positionSucess, positionError)  at begining of the file
function positionSucess(position) {
  //find the coordinates
  presentLatitude = position.coords.latitude;
  presentLongitude = position.coords.longitude;
  //change the coordinates in the form
  document.getElementsByName('latitude')[0].value = presentLatitude;
  document.getElementsByName('longitude')[0].value = presentLongitude;
  //update the map coordinates qith zoom 16
  mymap.setView([presentLatitude, presentLongitude], 16);
}

// used in navigator.geolocation.getCurrentPosition(positionSucess, positionError)  at begining of the file
function positionError() {
  alert('Unable to retrieve your location');
  document.getElementsByName('latitude')[0].value = "";
  document.getElementsByName('longitude')[0].value = "";
}

//this function will take the user's query and send it to the server, and capture the response and call displayStreetAjaxOutput()
function requestStreetAddressAjax(query) {
  if (query.length <= 2)
    return 0;

  var xhttp = new XMLHttpRequest();

  xhttp.open("GET", "browseController.php?searchType=address&streetAddress=" + query, true);
  xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhttp.send();

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {

      //capture ajax response which is json
      var response = xhttp.responseText;
      //send the response to the function that will display the suggestions
      displayStreetAjaxOutput(response);
    }
  };
}

//display the suggestions in the datalist dropdown under streetAddress
function displayStreetAjaxOutput(response) {
  //convert the string sent by ajax to json format
  jsonObjResponse = JSON.parse(response);
  //console.log(jsonObjResponse)

  //select the datalist element
  var dataList = document.getElementsByTagName('datalist')[0];
  //select all the options inside the datalist
  var dataListOptions = dataList.children;
  //add the list of street names to the datalist options
  for (let index = 0; index < jsonObjResponse.length; index++) {
    flag = 0;
    //loop through the options to see if the response to be added into the datalist already exists. if exists, change flag to 1
    for (let i = 0; i < dataListOptions.length; i++)
      if (dataListOptions[i].value == jsonObjResponse[index])
        flag = 1;

    //if flag=0, the response does not exist among the options, therefore add it to the datalist
    if (flag == 0)
      dataList.innerHTML += "<option value='" + jsonObjResponse[index] + "'></option>"
  }
}

//request the coordinates for the markers based on the updated radius
function requestMarkersAjax() {
  var xhttp = new XMLHttpRequest();

  xhttp.open("GET", "browseController.php?searchType=coordinates&latitude=" + presentLatitude + "&longitude=" + presentLongitude + "&distance=" + radius, true);
  xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhttp.send();

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {

      //capture ajax response which is json
      var response = xhttp.responseText;
      //send the response to the function that will display the suggestions
      displayMarkersByAjax(response);
    }
  };
}

//display the markers received as coordinates from requestMarkersAjax()
function displayMarkersByAjax(response) {
  jsonObjResponse = JSON.parse(response);

  //add the markers onto the map
  for (let index = 0; index < jsonObjResponse.length; index++)
    L.marker([jsonObjResponse[index].latitude, jsonObjResponse[index].longitude]).addTo(mymap).bindPopup(jsonObjResponse[index].latitude + "," + jsonObjResponse[index].longitude);


}