<?php
require_once('models/Database.php');
require_once('models/Users.php');
class UserDataset
{
  protected $_dbHandle, $_dbInstance;


  public function __construct()
  {
    //initiate the fields needed for the model
    $this->_dbInstance = Database::getInstance();
    $this->_dbHandle = $this->_dbInstance->getdbConnection();
  }

  //function to register new user
  public function insertUser($name, $email, $password, $isOwner, $ownerAddressFK)
  {
    try {
      $this->_dbHandle->beginTransaction();
      $sql = "INSERT INTO `users`(`name`, `email`, `password`, `isOwner`, `ownerAddressFK`) VALUES (:name,:email,:password,:isOwner,:ownerAddressFK)";
      $insert = $this->_dbHandle->prepare($sql);
      $insert->bindParam(':name', $name);
      $insert->bindParam(':email', $email);
      $insert->bindParam(':password', $password);
      $insert->bindParam(':isOwner', $isOwner);
      $insert->bindParam(':ownerAddressFK', $ownerAddressFK);
      $insert->execute();
      $user = new Users($this->_dbHandle->query("SELECT * FROM `users` WHERE `id`=" . $this->_dbHandle->lastInsertId())->fetch());

      $this->_dbHandle->commit();
      return $user;
    } catch (PDOException $e) {
      echo "we rolled back user insertion into table";
      echo $e->getMessage();
      $this->_dbHandle->rollBack();
    }
  }

  //function to check user login credentials
  public function checkUserLogin($email, $password)
  {
    try {
      $sql = "SELECT * FROM users WHERE email=? AND password=?";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $email);
      $stmt->bindParam(2, $password);
      $stmt->execute();
      return $stmt;
    } catch (PDOException $e) {
      echo "we rolled back user login";
      echo $e->getMessage();
    }
  }

  //function to retreive all user details
  public function readUser($userId)
  {
    try {
      $sql = "SELECT * FROM users WHERE id=?";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $userId);
      $stmt->execute();
      return $stmt->fetch();
    } catch (PDOException $e) {
      echo "we rolled back user fetching";
      echo $e->getMessage();
    }
  }

  //function to retreive all renter's name for a given chargepoint's ownerId
  public function readRenterNames($userId)
  {
    try {
      $sql = "SELECT name FROM users WHERE id IN (SELECT renterId FROM subscriptions WHERE ownerId=?)";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $userId);
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "we rolled back user name fetching";
      echo $e->getMessage();
    }
  }

  //function to change profile picture
  public function updateProfilePicture($userId, $profilePicture)
  {
    try {
      $this->_dbHandle->beginTransaction();
      $sql = "UPDATE users SET profile_pic=? WHERE id=?";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $profilePicture);
      $stmt->bindParam(2, $userId);
      $stmt->execute();
      $this->_dbHandle->commit();
      return 1;
    } catch (PDOException $e) {
      $this->_dbHandle->rollBack();
      return $e->getMessage();
    }
  }
}
