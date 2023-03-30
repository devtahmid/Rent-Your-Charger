<?php
//assume
require_once('models/Database.php');
require_once('models/Users.php');

//check if logged in from previous session
if (!isset($_SESSION['user'])) {
  require("views/login.phtml");
}
/* elseif (condition) {
  # code...
} */
