<?php
require_once('models/UserDataset.php');
require_once('models/AddressesDataset.php');

$emailRegex = '/^[a-zA-Z0-9._-]+@([a-zA-Z0-9-]+\.)+[a-zA-Z.]{2,5}$/';
$passwordRegex = '/^[0-9A-Za-z]{6,16}$/';
$rateRegex = '/^\d{1,3}(\.\d{1,3})?$/';
$latitudeRegex = '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/';
$longitudeRegex = '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/';

if (session_status() !== PHP_SESSION_ACTIVE)
  session_start();


if (isset($_SESSION['userId'])) {
  if ($_SESSION['userType'] == "owner")
    require_once('dashboardController.php');
  elseif ($_SESSION['userType'] == "renter")
    require_once('browseController.php');

  exit();
}

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

    //sanitize user input
    $postEmail = htmlspecialchars(stripslashes(strip_tags($_POST['email'])));
    $postPassword = htmlspecialchars(stripslashes(strip_tags($_POST['password'])));
    $postPassword = md5($postPassword); //hashed password
    $userModel = new UserDataset();
    $queriedResult = $userModel->checkUserLogin($postEmail, $postPassword);
    $userRow = $queriedResult->fetch();
    //var_dump($userRow);
    if ($queriedResult->rowCount() == 0) {
      $error = "wrong email or password";
      require("views/login.phtml");
    } else { //email and password found in db
      if (session_status() !== PHP_SESSION_ACTIVE)
        session_start();

      $_SESSION['userId'] = $userRow['id'];

      if ($userRow['isOwner'] == "true") {
        $_SESSION['userType'] = "owner";
        require_once('dashboardController.php');
      } else {
        $_SESSION['userType'] = "renter";
        require_once('browseController.php');
      }
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
  } elseif ($_POST['userType'] == 'owner' && (!isset($_POST['street_address']) || !isset($_POST['longitude']) || !isset($_POST['latitude']))) {
    $error2 = "please fill in all address fields";
    require("views/login.phtml");
  } elseif ($_POST['userType'] == 'owner' && (!preg_match($latitudeRegex, $_POST['latitude']) || !preg_match($longitudeRegex, $_POST['longitude']))) {
    $error2 = "Invalid latitude/longitude format";
    require("views/login.phtml");
  } elseif ($_POST['userType'] == 'owner' && !preg_match($rateRegex, $_POST['rate'])) {
    $error2 = "Invalid rate format";
    require("views/login.phtml");
  } else {
    $userId;
    //all inputs are valid. now insert into db

    //but first sanitize user input
    $postStreetAddress = htmlspecialchars(stripslashes(strip_tags($_POST['street_address'])));
    $postLatitude = htmlspecialchars(stripslashes(strip_tags($_POST['latitude'])));
    $postLongitude = htmlspecialchars(stripslashes(strip_tags($_POST['longitude'])));
    $postRate = htmlspecialchars(stripslashes(strip_tags($_POST['rate'])));

    $postName = htmlspecialchars(stripslashes(strip_tags($_POST['name'])));
    $postEmail = htmlspecialchars(stripslashes(strip_tags($_POST['email'])));
    $postPassword = htmlspecialchars(stripslashes(strip_tags($_POST['password'])));
    $postPassword = md5($postPassword); //hashed password
    if ($_POST['userType'] == 'owner') {


      //insert address into db
      $addressModel = new AddressesDataset();
      $insertedAddress = $addressModel->insertAddress($postStreetAddress, $postLatitude, $postLongitude,  $postRate);
      $addressId = $insertedAddress->getID();
      // insert user into db
      $userModel = new UserDataset();
      $insertedUser = $userModel->insertUser($postName, $postEmail, $postPassword, "true", $addressId);
      $userId = $insertedUser->getID();
    } else {
      // register user as a renter
      $userModel = new UserDataset();
      $insertedUser = $userModel->insertUser($postName, $postEmail, $postPassword, 'false', 0);
      $userId = $insertedUser->getID();
    }
    if (session_status() !== PHP_SESSION_ACTIVE)
      session_start();

    $_SESSION['userId'] = $userId;

    if ($insertedUser->getIsOwner() == "true") {
      $_SESSION['userType'] = 'owner';
    } else
      $_SESSION['userType'] = 'renter';

    if ($_SESSION['userType'] == 'owner')
      require_once('dashboardController.php');
    elseif ($_SESSION['userType'] == 'renter')
      require_once('browseController.php');
  }
}
