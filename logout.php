<?php
session_start();
unset($_SESSION['userId']);
unset($_SESSION['userType']);

session_unset();
session_destroy();
//below from php website session unset function
session_write_close();
setcookie(session_name(), '', 0, '/');
require_once('credentialController.php');
