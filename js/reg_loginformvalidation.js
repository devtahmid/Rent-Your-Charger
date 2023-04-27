//this page is used to validate the inputs in login and sign up page

var nameFlag = emailFlag = passwordFlag = cnfmpasswordFlag = streetFlag = longitudeFlag = latitudeFlag = rateFlag = false;
function checkFN(name) { //check full name
  var nameExp = /^([a-z]+\s)*[a-z]+$/i;
  if (name.length == 0) {
    msg = "";
    nameFlag = false;
  }
  else if (!nameExp.test(name)) {
    msg = "Invalid Name";
    color = "red";
    nameFlag = false;
  }
  else {
    msg = "Valid Name";
    color = "green";
    nameFlag = true;
  }
  document.getElementById('name_msg').style.color = color;
  document.getElementById('name_msg').innerHTML = msg;
}

function checkPWD(pwd, id) { //check password

  var pwdExp = /^[0-9A-Za-z]{6,16}$/;
  if (pwd.length == 0) {
    msg = "";
    passwordFlag = false;
  }
  else if (!pwdExp.test(pwd)) {
    msg = "Invalid password";
    color = "red";
    passwordFlag = false;
  }
  else {
    msg = "Valid password";
    color = "green";
    passwordFlag = true;
  }
  console.log(passwordFlag)
  document.getElementById(id).style.color = color; //id is used because same function use for login form where id is different
  document.getElementById(id).innerHTML = msg;
  if (id == "reg_pwd_msg")  //did this cus if user enters a valid passord after entering a valid comfirmation password
    confirmPWD(document.forms[1].confirm_password.value);
}

function confirmPWD(cpassword) { //check 2nd password
  if (cpassword.length == 0) {
    msg = "";
    cnfmpasswordFlag = false;
  }
  else if (document.getElementById('reg_pwd_msg').innerHTML == 'Invalid password') { //typing confirm password but first password is not valid
    msg = "enter valid password first";
    color = "red";
    cnfmpasswordFlag = false;
  }
  else {
    //cpassword not empty and firstpassword is valid
    var firstPwd = document.forms[1].password.value;
    if (firstPwd.length == 0) {
      msg = "";
      cnfmpasswordFlag = false;
      color = "white"; //need to enter or gives not defined error
    }
    else if (cpassword != firstPwd) {
      msg = "passwords don't match";
      color = "red";
      cnfmpasswordFlag = false;

    }
    else {
      msg = "they match";
      color = "green";
      cnfmpasswordFlag = true;
    }
  }
  document.getElementById('cfmpwd_msg').style.color = color;
  document.getElementById('cfmpwd_msg').innerHTML = msg;
}

function checkMAIL(mail, typeOfForm) { //check mail format
  var mailExp = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9-]+\.)+[a-zA-Z.]{2,5}$/;
  if (mail.length == 0) {
    msg = "";
    emailFlag = false;
  }
  else if (!mailExp.test(mail)) {
    msg = "Invalid mail format";
    color = "red";
    emailFlag = false;
  }
  else {
    msg = "Valid mail";
    color = "green";
    emailFlag = true;
  }
  console.log(emailFlag)
  document.getElementById(typeOfForm).style.color = color;
  document.getElementById(typeOfForm).innerHTML = msg;
}

function checkStreet(street) { //check streetAddress
  if (street.length <= 2) {
    msg = "Street Address minimum 3 characters";
    color = "red";
    streetFlag = false;
  }
  else {
    msg = "Valid";
    color = "green";
    streetFlag = true;
  }
  document.getElementById('street_msg').style.color = color;
  document.getElementById('street_msg').innerHTML = msg;
}

function checkLatitude(latitude) { //check latitude
  var latitudeExp = /^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/;
  if (latitude.length == 0) {
    msg = "";
    latitudeFlag = false;
  }
  else if (!latitudeExp.test(latitude)) {
    msg = "Invalid latitude";
    color = "red";
    latitudeFlag = false;
  }
  else {
    msg = "Valid latitude";
    color = "green";
    latitudeFlag = true;
  }
  document.getElementById('latitude_msg').style.color = color;
  document.getElementById('latitude_msg').innerHTML = msg;
}

function checkLongitude(longitude) { //check longitude
  var longitudeExp = /^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/;
  if (longitude.length == 0) {
    msg = "";
    longitudeFlag = false;
  }
  else if (!longitudeExp.test(longitude)) {
    msg = "Invalid lngitude";
    color = "red";
    longitudeFlag = false;
  }
  else {
    msg = "Valid longitude";
    color = "green";
    longitudeFlag = true;
  }
  document.getElementById('longitude_msg').style.color = color;
  document.getElementById('longitude_msg').innerHTML = msg;
}

function checkRate(rate) { //check rate
  var rateExp = /^\d{1,3}(\.\d{1,3})?$/;
  if (rate.length == 0) {
    msg = "";
    color = "red"; //need default color for input type=number because when letters are entered, string length =0 and error: color not defined , at end of function
    rateFlag = false;
  }
  else if (!rateExp.test(rate)) {
    msg = "Invalid rate";
    color = "red";
    rateFlag = false;
  }
  else {
    msg = "Valid rate";
    color = "green";
    rateFlag = true;
  }
  document.getElementById('rate_msg').style.color = color;
  document.getElementById('rate_msg').innerHTML = msg;
}



function checkRegistrationInputs() {
  document.forms[1].JSEnabled.value = "TRUE";
  if (document.getElementById('userTypeOwner').checked)
    return (nameFlag = emailFlag = passwordFlag = cnfmpasswordFlag = streetFlag = longitudeFlag = latitudeFlag = rateFlag);
  else
    return (nameFlag = emailFlag = passwordFlag = cnfmpasswordFlag);

}

function checkLoginInputs() {
  document.forms[0].JSEnabled.value = "TRUE";
  return (emailFlag && passwordFlag);
}
