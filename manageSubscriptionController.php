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

//calculating total charges incurred
$totalCharges = 0;
foreach ($renterSubscriptions as $subscription) {
  if ($subscription['status'] == 'cancelled') {
    $numberOfDays = (strtotime($subscription['cancelledDate']) - strtotime($subscription['startDate'])) / (60 * 60 * 24);
    //difference in starttime and endtime
    $timePeriodInHours = (strtotime($subscription['endTime']) - strtotime($subscription['startTime'])) / (60 * 60);

    $totalCharges += $numberOfDays * $timePeriodInHours * $addressRow['rate'];
  } elseif ($subscription['status'] == 'renting') {
    $numberOfDays = (strtotime(date("Y-m-d")) - strtotime($subscription['startDate'])) / (60 * 60 * 24);

    //difference in starttime and endtime
    $timePeriodInHours = (strtotime($subscription['endTime']) - strtotime($subscription['startTime'])) / (60 * 60);

    $totalCharges += $numberOfDays * $timePeriodInHours * $addressRow['rate'];
  }
}



require_once("views/manageSubscription.phtml");
