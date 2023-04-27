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
date_default_timezone_set('Asia/Riyadh');
$renterId = $_SESSION['userId'];
echo $renterId;
if (!isset($_GET['address']) && !isset($_GET['submitForm'])) {
  require_once("views/browse.phtml");
  exit();
} elseif (isset($_GET['address'])) { //came to new subscription page from browse page
  $addressId = $_GET['address'];
  $userModel = new UserDataset();
  $userRow = $userModel->findAddressOwner($addressId);
  $ownerId = $userRow['id']; //needed for address['ownerId'] field in DB . set as hidden input in newSubscription.phtml
  //to display chargepoint details
  $addressModel = new AddressesDataset();
  $addressRow = $addressModel->readAddress($addressId);

  //to display occupied slots
  $subscriptionModel = new SubscriptionsDataset();
  $occupiedSlots = $subscriptionModel->readOccupiedSlots($ownerId);

  require_once("views/newSubscription.phtml");
} elseif (isset($_GET['submitForm'])) { //submitted new subscrition

  //sanitizing inputs
  $getAddressIdHidden =  htmlspecialchars(stripslashes(strip_tags($_GET['addressIdHidden'])));
  $getStartTime =  htmlspecialchars(stripslashes(strip_tags($_GET['startTime'])));
  $getEndTime =  htmlspecialchars(stripslashes(strip_tags($_GET['endTime'])));
  $getDate =  htmlspecialchars(stripslashes(strip_tags($_GET['date'])));

  $addressId = $getAddressIdHidden;
  $userModel = new UserDataset();
  $userRow = $userModel->findAddressOwner($getAddressIdHidden); //need to know ownerId while inserting new subscription
  $ownerId = $userRow['id'];


  $subscriptionModel = new SubscriptionsDataset();
  $occupiedSlots = $subscriptionModel->readOccupiedSlots($ownerId);

  // check if clashing with already booked slots
  $clashingSlots = $subscriptionModel->checkIfTimeClashes($ownerId, $getStartTime, $getEndTime, $getDate);

  if ($clashingSlots > 0) {
    $error = "Time period clashes with already booked slots";
    header("location :browseController.php");
    exit();
  } else { // subscription can be done
    $insertedSubscription = $subscriptionModel->insertSubscription($renterId, $ownerId,  $getDate, $getStartTime, $getEndTime);

    if (!isset($insertedSubscription)) {
      $error = "Subscription failed";
      var_dump($insertedSubscription);
      require_once("views/newSubscription.phtml");
      exit();
    }

    //subscription done
    require_once("manageSubscriptionController.php");
  }
}
