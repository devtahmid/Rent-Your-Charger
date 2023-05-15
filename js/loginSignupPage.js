//this page is used for the animation togglig of login and signup page
//it is also used to show the address fields if the usertype is selected as owner

//by default ownerr fields are not displayed
document.getElementsByName('ownerFields').forEach(function (element) {
  element.style.display = 'none';
});

//below is for styling
const loginText = document.querySelector(".title-text .login");
const loginForm = document.querySelector("form.login");
const loginBtn = document.querySelector("label.login");
const signupBtn = document.querySelector("label.signup");
signupBtn.onclick = (() => {
  loginForm.style.marginLeft = "-50%";
  loginText.style.marginLeft = "-50%";
});
loginBtn.onclick = (() => {
  loginForm.style.marginLeft = "0%";
  loginText.style.marginLeft = "0%";
  //dont display ownerFields when in loginForm otherwise form will be too long (remove the below loop and check)
  document.getElementsByName('ownerFields').forEach(function (element) {
    element.style.display = 'none';
  });

});

//checkbox toggle
function toggleAdditionalDetails() {
  var radioButton = document.getElementsByName('userType')[0];
  var ownerFields = document.getElementsByName('ownerFields');

  if (radioButton.checked && radioButton.value == 'owner') {
    ownerFields.forEach(function (element) {
      element.style.display = 'block';
    });
    document.getElementsByName('street_address')[0].required = true;
    document.getElementsByName('latitude')[0].required = true;
    document.getElementsByName('longitude')[0].required = true;
    document.getElementsByName('rate')[0].required = true;
  } else {
    ownerFields.forEach(function (element) {
      element.style.display = 'none';
    });
    document.getElementsByName('street_address')[0].required = false;
    document.getElementsByName('latitude')[0].required = false;
    document.getElementsByName('longitude')[0].required = false;
    document.getElementsByName('rate')[0].required = false;
  }


}

document.getElementById('popupClose').addEventListener('click', displaySampleCredentials);

function displaySampleCredentials() {
  if (document.getElementById('credentialsPopup').style.display == 'none')
    document.getElementById('credentialsPopup').style.display = 'block';
  else
    document.getElementById('credentialsPopup').style.display = 'none'

  return false; //because a submit button calling it
}