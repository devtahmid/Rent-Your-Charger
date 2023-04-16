<?php
//handles how users browse the app after login
require_once('models/UserDataset.php');
require_once('models/AddressesDataset.php');
require_once('models/SubscriptionsDataset.php');

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['userId'])) {
  require_once('credentialController.php');
  exit();
}

require_once("views/ownernavbar.phtml");
$OwnerUserId = $_SESSION['userId'];
$userModel = new UserDataset();
$OwnerUserRow = $userModel->readUser($OwnerUserId);

//pagination

if (!isset($_GET['page']))
  $page = 1;
else
  $page = $_GET['page'];

$results_per_page = 2;
$page_first_result = ($page - 1) * $results_per_page;

$subscriptionModel = new SubscriptionsDataset();
$numberOfSubscriptions = $subscriptionModel->readNumberOfSubscriptions($OwnerUserId); //total number of subscriptions
$numberOfPages = ceil($numberOfSubscriptions / $results_per_page); //total number of pages

$subscriptions = $subscriptionModel->readChargepointRentersPagination($OwnerUserId, $page_first_result, $results_per_page);



$addressModel = new AddressesDataset();
$addressRow = $addressModel->readAddress($OwnerUserRow['ownerAddressFK']);


$allSubscriptions = $subscriptionModel->readChargepointRenters($OwnerUserId);
$totalRevenue = 0;

foreach ($allSubscriptions as $subscription) {
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
