<?php

require_once('models/UserDataset.php');
require_once('models/AddressesDataset.php');
require_once('models/SubscriptionsDataset.php');

if (session_status() !== PHP_SESSION_ACTIVE)
  session_start();

if (!isset($_SESSION['userId'])) {
  require_once('credentialController.php');
  exit();
}

require_once("views/renternavbar.phtml");

$subscriptionModel = new SubscriptionsDataset();


if (isset($_GET['submitCancellation'])) {
  $cancelledSubscription = $subscriptionModel->cancelSubscription($_GET['subscriptionId']);
}


$renterId = $_SESSION['userId'];

$renterSubscriptions = $subscriptionModel->readRenterSubscriptions($renterId);


$addressModel = new AddressesDataset();
$userModel = new UserDataset();
$streetAddressArray =[]; // this array will collect all the street addresses for the ownerIds in the $renterSubscriptions array


//calculating total charges incurred
$totalCharges = 0;
foreach ($renterSubscriptions as $subscription) {
  if ($subscription['status'] == 'cancelled') {
    $numberOfDays = (strtotime($subscription['cancelledDate']) - strtotime($subscription['startDate'])) / (60 * 60 * 24);
    //difference in starttime and endtime
    $timePeriodInHours = (strtotime($subscription['endTime']) - strtotime($subscription['startTime'])) / (60 * 60);

    //for a given ownerId, we need to find its ownerAddressFK which is the addressId. This will help us get the rate associated with the address
    $ownerRow = $userModel->readUser($subscription['ownerId']);
    $addressRow = $addressModel->readAddress($ownerRow['ownerAddressFK']); //used to collect streetAddress and also to get the rate associated with the address
    $streetAddressArray[] = $addressRow['streetAddress'];

    $totalCharges += $numberOfDays * $timePeriodInHours * $addressRow['rate'];
  } elseif ($subscription['status'] == 'renting') {
    $numberOfDays = (strtotime(date("Y-m-d")) - strtotime($subscription['startDate'])) / (60 * 60 * 24);

    //difference in starttime and endtime
    $timePeriodInHours = (strtotime($subscription['endTime']) - strtotime($subscription['startTime'])) / (60 * 60);

    //for a given ownerId, we need to find its ownerAddressFK which is the addressId. This will help us get the rate associated with the address
    $ownerRow = $userModel->readUser($subscription['ownerId']);
    $addressRow = $addressModel->readAddress($ownerRow['ownerAddressFK']); //used to collect streetAddress and also to get the rate associated with the address
    $streetAddressArray[] = $addressRow['streetAddress'];

    $totalCharges += $numberOfDays * $timePeriodInHours * $addressRow['rate'];
  }
}



require_once("views/manageSubscription.phtml");
