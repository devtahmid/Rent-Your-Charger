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


}