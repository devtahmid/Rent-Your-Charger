<?php
require_once('models/UserDataset.php');
require_once('models/AddressesDataset.php');
require_once('models/SubscriptionsDataset.php');

session_start();
if (!isset($_SESSION['userId'])) {
  require_once('credentialController.php');
  exit();
}

$sid = $_SESSION['userId'];
$userModel = new UserDataset();
$userProfile = $userModel->readUser($sid);

$error;
//if profileController.php is called for a file upload
if (isset($_POST['edit_user']) && isset($_FILES["picfile"]["name"]) && $_FILES["picfile"]["name"] != $userProfile['profile_pic']) { //if statement to decide if new pic uploaded
  if ((($_FILES["picfile"]["type"] == "image/gif")
      || ($_FILES["picfile"]["type"] == "image/jpeg")
      || ($_FILES["picfile"]["type"] == "image/png")
      || ($_FILES["picfile"]["type"] == "image/pjpeg"))
    && ($_FILES["picfile"]["size"] < 5000000)
  ) {
    if ($_FILES["picfile"]["error"] > 0) {
      $error = $_FILES["picfile"]["error"];
    } else {
      $fdetails = explode(".", $_FILES["picfile"]["name"]);
      $fext = end($fdetails);
      $fileName = "pic" . $fdetails[0] . time() . uniqid(rand()) . ".$fext";  //file name
      if (move_uploaded_file($_FILES["picfile"]["tmp_name"], "views/assets/image/$fileName")) {
        //Storage: views/assets/image/$fileName;
        //pic moved, now enter image details into db
        $fileUploaded = $userModel->updateProfilePicture($sid, $fileName);
        if ($fileUploaded != 1) {
          $error = "DB returned: " . $fileUploaded;
          echo $error;
        }
      } else
        $error = "File upload failed";
    }
  } else
    $error = "Invalid file type or bigger than 5MB";
} //end of file-upload if statement


/* if ($_SESSION['userType'] == 'renter')
  require_once("views/renternavbar.phtml");
else
  require_once("views/ownernavbar.phtml"); */

echo $error;

$addressModel = new AddressesDataset();
$userAddress = $addressModel->readAddress($userProfile['ownerAddressFK']);

$name = $userProfile['name'];
$email = $userProfile['email'];
$coordinates = $userAddress['latitude'] . ", " . $userAddress['longitude'];
$streetName = $userAddress['streetAddress'];
$profile_pic = $userProfile['profile_pic'];



require_once("views/profile.phtml");
