<?php

require_once('models/UserDataset.php');
require_once('models/AddressesDataset.php');


if (session_status() !== PHP_SESSION_ACTIVE)
  session_start();

if (!isset($_SESSION['userId'])) { //if not logged in
  require_once('credentialController.php');
  exit();
}


$ownerId = $_SESSION['userId'];
$userModel = new UserDataset();
$ownerRow = $userModel->readUser($ownerId); // will be used to get ownerAddressFK which will be used to get AddressId

$addressModel = new AddressesDataset();

//if form is submitted for changing rate
if (isset($_GET['submitForm'])) {
  $updatedRate =  $addressModel->updateRate($ownerRow['ownerAddressFK'], $_GET['rate']);
  if ($updatedRate == 1) {
    echo "<script>alert('updated Successfully');</script>";
    require_once("dashboardController.php");
    exit();
  } else
    $error = $updatedRate;
}


$addressModel = new AddressesDataset();
$addressRow = $addressModel->readAddress($ownerRow['ownerAddressFK']); //used to collect streetAddress and also to get the rate associated with the address

require_once("views/ownernavbar.phtml");
require_once("views/changeRate.phtml");
