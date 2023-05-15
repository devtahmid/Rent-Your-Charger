<?php
//assume
require_once('models/Database.php');
require_once('models/Users.php');

if (session_status() !== PHP_SESSION_ACTIVE)
  session_start();


//check if logged in from previous session
if (!isset($_SESSION['userId'])) {
  require("views/login.phtml");
} elseif ($_SESSION['userType'] == 'owner') {
  require_once('dashboardController.php');
} elseif ($_SESSION['userType'] == 'renter') {
  require_once('browseController.php');
}
