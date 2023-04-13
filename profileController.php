<?php
session_start();
if (!isset($_SESSION['userId'])) {
  require_once('credentialController.php');
  exit();
}
$sid = $_SESSION['userId'];
try {
  require('project_connection.php');
  $userResult = $db->prepare("SELECT * FROM users WHERE ID= :id");
  $userResult->bindParam(':id', $sid);
  $userResult->execute();
  $userRow = $userResult->fetch();
  $password = $userRow['PASSWORD'];
  $name = $userRow['NAME'];
  $mobile = $userRow['PHONE'];
  $email = $userRow['EMAIL'];
  $country = "Bahrain"; //check comment under form.select in above if statement
  $profile_pic = $userRow['PROFILE_PIC'];
  $db = null;
} catch (PDOException $e) {
  echo "<script>alert('Error " . $e->getMessage() . "\\nPlease refresh');</script>"; //paste in b/w ".$e->getMessage()."  to see errror

}
?>

$_SESSION['userType'] = $insertedUser->getIsOwner() ? 'owner' : 'renter';