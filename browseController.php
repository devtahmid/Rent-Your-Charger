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
    //sanitize input
    $getAddress = htmlspecialchars(stripslashes(strip_tags($_GET['streetAddress'])));


    $maxResultsToFetch = 10;
    $addressModel = new AddressesDataset();
    $matchedAddresses = $addressModel->matchAddressByRenterInputStreetAddress($getAddress, $price, 0, $maxResultsToFetch);

    //convert $matchedAddresses to json and send to client

    $streetNamesArray = [];
    foreach ($matchedAddresses as $address)
      array_push($streetNamesArray, $address['streetAddress']);

    echo json_encode($streetNamesArray);
  } elseif ($_GET['searchType'] == 'coordinates') {
    //sanitize inputs
    $getLatitude = htmlspecialchars(stripslashes(strip_tags($_GET['latitude'])));
    $getLongitude = htmlspecialchars(stripslashes(strip_tags($_GET['longitude'])));
    $getDistance = htmlspecialchars(stripslashes(strip_tags($_GET['distance'])));
    $addressModel = new AddressesDataset();

    $matchedAddresses = $addressModel->matchAddressByRenterInputCoordinates($getLatitude, $getLongitude, $getDistance, $price, 0, 1100);
    //maximum 1100 markers will be returned. anyway, the map shows error after around 800 markers

    $markersArray = [];
    foreach ($matchedAddresses as $address) {
      $marker = [];
      $marker['latitude'] = $address['latitude'];
      $marker['longitude'] = $address['longitude'];
      array_push($markersArray, $marker);
    }
    echo json_encode($markersArray);
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

  //total numberr of matches. 0 is passed as the last parameter to indicate that we only want the total number of matches
  $numberOfMatches = $addressModel->matchAddressByRenterInputStreetAddress($_GET['streetAddress'], $price, $page_first_result, 0);


  $numberOfPages = ceil($numberOfMatches / $results_per_page);
  //100/3 = 33.3333
  //ceil(33.333) = 34
  $matchedAddresses = $addressModel->matchAddressByRenterInputStreetAddress($_GET['streetAddress'], $price, $page_first_result, $results_per_page);
} elseif ($_GET['searchType'] == 'coordinates') {

  $numberOfMatches = $addressModel->matchAddressByRenterInputCoordinates($_GET['latitude'], $_GET['longitude'], $distance, $price, $page_first_result, 0);

  $numberOfPages = ceil($numberOfMatches / $results_per_page);

  $matchedAddresses = $addressModel->matchAddressByRenterInputCoordinates($_GET['latitude'], $_GET['longitude'], $distance, $price, $page_first_result, $results_per_page);
}

require_once("views/browse.phtml");
