<?php
//handles how users browse the app after login
require_once('models/UserDataset.php');
require_once('models/AddressesDataset.php');
require_once('models/SubscriptionsDataset.php');

require_once("views/ownernavbar.phtml");


session_start();
$OwnerUserId = $_SESSION['userId'];
$userModel = new UserDataset();
$OwnerUserRow = $userModel->readUser($OwnerUserId);

$addressModel = new AddressesDataset();
$addressRow = $addressModel->readAddress($OwnerUserRow['ownerAddressFK']);

$subscriptionModel = new SubscriptionsDataset();
$subscriptions = $subscriptionModel->readChargepointRenters($OwnerUserId);
$totalRevenue = 0;

foreach ($subscriptions as $subscription) {
  if ($subscription['status'] == 'cancelled') {
    $numberOfDays = (strtotime($subscription['cancelledDate']) - strtotime($subscription['startDate'])) / (60 * 60 * 24);
    //difference in starttime and endtime
    $timePeriodInHours = (strtotime($subscription['endTime']) - strtotime($subscription['startTime'])) / (60 * 60);

    $totalRevenue += $numberOfDays * $timePeriodInHours * $addressRow['rate'];
  } elseif ($subscription['status'] == 'renting') {
    $numberOfDays = (strtotime(date("Y-m-d")) - strtotime($subscription['startDate'])) / (60 * 60 * 24);

    //difference in starttime and endtime
    $timePeriodInHours = (strtotime($subscription['endTime']) - strtotime($subscription['startTime'])) / (60 * 60);

    $totalRevenue += $numberOfDays * $timePeriodInHours * $addressRow['rate'];
  }
}

$allRenterNames = $userModel->readRenterNames($OwnerUserId);
require_once("views/ownerdashboard.phtml");
