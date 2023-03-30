<?php
require_once('models/UserDataset.php');

$emailRegex = '/^[a-zA-Z0-9._-]+@([a-zA-Z0-9-]+\.)+[a-zA-Z.]{2,5}$/';
$passwordRegex = '/^[0-9A-Za-z]{6,16}$/';

if (!isset($_POST['submit']))
  require("views/login.phtml");
elseif ($_POST['submit'] == 'Login') {
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
    if (isset($userRow)) {
      $error = "wrong email or password";
      require("views/login.phtml");
    } else //email and password found in db
      require_once('browserController.php');
  }
} elseif ($_POST['submit'] == 'Signup') {
}
