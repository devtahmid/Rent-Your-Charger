<?php

require_once('models/UserDataset.php');
require_once('models/AddressesDataset.php');
require_once('models/SubscriptionsDataset.php');

//this function will check if the request received by browseController.php is an ajax request
function is_ajax_request()
{
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

if (is_ajax_request()) {
  //if the request is an ajax request, then we will return the json data
  $price = 0;
  if ($_GET['searchType'] == 'address') {

    $maxResultsInDropDown = 10;
    $addressModel = new AddressesDataset();
    $matchedAddresses = $addressModel->matchAddressByRenterInputStreetAddress($_GET['streetAddress'], $price, 0, $maxResultsInDropDown);

    //convert $matchedAddresses to json and send to client

    $streetNamesArray = [];
    foreach ($matchedAddresses as $address)
      array_push($streetNamesArray, $address['streetAddress']);

    echo json_encode($streetNamesArray);
  }

  exit();
}




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
$renterSubscriptions = $subscriptionModel->readRenterSubscriptionsWithAddressId($renterId);

$price = 0;
$distance = 0;
if (isset($_GET['priceCheckbox']))
  $price = $_GET['price'];
if (isset($_GET['distanceCheckbox']))
  $distance = $_GET['distance'];



if (!isset($_GET['page']))
  $page = 1;
else
  $page = $_GET['page'];

$results_per_page = 3;
$page_first_result = ($page - 1) * $results_per_page;

$numberOfPages;

$addressModel = new AddressesDataset();
if ($_GET['searchType'] == 'address') {

  $numberOfMatches = $addressModel->matchAddressByRenterInputStreetAddress($_GET['streetAddress'], $price, $page_first_result, 0);

  $numberOfPages = ceil($numberOfMatches / $results_per_page);

  $matchedAddresses = $addressModel->matchAddressByRenterInputStreetAddress($_GET['streetAddress'], $price, $page_first_result, $results_per_page);
} elseif ($_GET['searchType'] == 'coordinates') {

  $numberOfMatches = $addressModel->matchAddressByRenterInputCoordinates($_GET['latitude'], $_GET['longitude'], $distance, $price, $page_first_result, 0);

  $numberOfPages = ceil($numberOfMatches / $results_per_page);

  $matchedAddresses = $addressModel->matchAddressByRenterInputCoordinates($_GET['latitude'], $_GET['longitude'], $distance, $price, $page_first_result, $results_per_page);
}

require_once("views/browse.phtml");
