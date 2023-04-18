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

if (!isset($_GET['searchType'])) {
  require_once("views/browse.phtml");
  exit();
}

$renterId = $_SESSION['userId'];
$subscriptionModel = new SubscriptionsDataset();
$renterSubscriptions = $subscriptionModel->readRenterSubscriptions($renterId);

$price = 0;
$distance = 0;
if (isset($_GET['priceCheckbox']))
  $price = $_GET['price'];
if (isset($_GET['distanceCheckbox']))
  $distance = $_GET['distance'];

$addressModel = new AddressesDataset();
if ($_GET['searchType'] == 'address')
  $matchedAddresses = $addressModel->matchAddressByRenterInputStreetAddress($_GET['streetAddress'], $price);

elseif ($_GET['searchType'] == 'coordinates')
  $matchedAddresses = $addressModel->matchAddressByRenterInputCoordinates($_GET['latitude'], $_GET['longitude'], $distance, $price);


require_once("views/browse.phtml");
