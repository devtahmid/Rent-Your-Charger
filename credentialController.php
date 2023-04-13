<?php
require_once('models/UserDataset.php');
require_once('models/AddressesDataset.php');

$emailRegex = '/^[a-zA-Z0-9._-]+@([a-zA-Z0-9-]+\.)+[a-zA-Z.]{2,5}$/';
$passwordRegex = '/^[0-9A-Za-z]{6,16}$/';
$rateRegex = '/^\d+(\.\d{1,3})?$/';
$latitudeRegex = '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/';
$longitudeRegex = '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/';
if (!isset($_POST['submit']))
  require("views/login.phtml");
elseif ($_POST['submit'] == 'Login') {  // if login clicked
  if (!preg_match($emailRegex, $_POST['email'])) {
    $error = "invalid email format";
    require("views/login.phtml");
  } elseif (!preg_match($passwordRegex, $_POST['password'])) {
    $error = "invalid password format";
    require("views/login.phtml");
  } else { //input formats are valid. now check if in db
    $userModel = new UserDataset();
    $queriedResult = $userModel->checkUserLogin($_POST['email'], $_POST['password']);
    $userRow = $queriedResult->fetch();
    if (!isset($userRow)) {
      $error = "wrong email or password";
      require("views/login.phtml");
    } else { //email and password found in db
      session_start();
      $_SESSION['userId'] = $userRow['id'];
      $_SESSION['userType'] = $userRow['isOwner'] ? 'owner' : 'renter';

      if ($_SESSION['userType'] == 'owner')
        require_once('dashboardController.php');
      elseif ($_SESSION['userType'] == 'renter')
        require_once('browseController.php');
    }
  }
} elseif ($_POST['submit'] == 'Signup') {   //if sign up clicked

  if (!preg_match($emailRegex, $_POST['email'])) {
    $error2 = "invalid email format";
    require("views/login.phtml");
  } elseif ($_POST['password'] != $_POST['confirm_password']) {
    $error2 = "passwords do not match";
    require("views/login.phtml");
  } elseif (!preg_match($passwordRegex, $_POST['password']) || !preg_match($passwordRegex, $_POST['confirm_password'])) {
    $error2 = "invalid password format";
    require("views/login.phtml");
  } elseif (isset($_POST['isOwner']) && (!isset($_POST['street_address']) || !isset($_POST['longitude']) || !isset($_POST['latitude']))) {
    $error2 = "please fill in all address fields";
    require("views/login.phtml");
  } elseif (isset($_POST['isOwner']) && (!preg_match($latitudeRegex, $_POST['latitude']) || !preg_match($longitudeRegex, $_POST['longitude']))) {
    $error2 = "Invalid latitude/longitude format";
    require("views/login.phtml");
  } elseif (isset($_POST['isOwner']) && !preg_match($rateRegex, $_POST['rate'])) {
    $error2 = "Invalid rate format";
    require("views/login.phtml");
  } else {
    $userId;
    //all inputs are valid. now insert into db
    if (isset($_POST['isOwner'])) {
      //insert address into db
      $addressModel = new AddressesDataset();
      $insertedAddress = $addressModel->insertAddress($_POST['street_address'], $_POST['latitude'], $_POST['longitude'],  $_POST['rate']);
      $userId = $insertedAddress->getID();
      // insert user into db
      $userModel = new UserDataset();
      $insertedUser = $userModel->insertUser($_POST['name'], $_POST['email'], $_POST['password'], "true", $userId);
    } else {
      // register user as a renter
      $userModel = new UserDataset();
      $insertedUser = $userModel->insertUser($_POST['name'], $_POST['email'], $_POST['password'], 'false', 0);
      $userId = $insertedUser->getID();
    }
    session_start();
    $_SESSION['userId'] = $userId;
    $_SESSION['userType'] = $insertedUser->getIsOwner() ? 'owner' : 'renter';

    if ($_SESSION['userType'] == 'owner')
      require_once('dashboardController.php');
    elseif ($_SESSION['userType'] == 'renter')
      require_once('browseController.php');
  }
}
