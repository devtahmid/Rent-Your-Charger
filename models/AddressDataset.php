<?php
require_once('models/Database.php');
require_once('models/Users.php');
class AddressDataset
{
  protected $_dbHandle, $_dbInstance;


  public function __construct()
  {
    //initiate the fields needed for the model
    $this->_dbInstance = Database::getInstance();
    $this->_dbHandle = $this->_dbInstance->getdbConnection();
  }
//id, streetAddress , latitude, longitude, price
  public function insertAddress($streetAddress, $latitude, $longitude, $price)
  {
    try {
      $this->_dbHandle->beginTransaction();
      $sql = "INSERT INTO `address`(`streetAddress`, `latitude`, `longitude`, `price`) VALUES (:streetAddress,:latitude,:longitude,:price)";
      $insert = $this->_dbHandle->prepare($sql);
      $insert->bindParam(':streetAddress', $streetAddress);
      $insert->bindParam(':latitude', $latitude);
      $insert->bindParam(':longitude', $longitude);
      $insert->bindParam(':price', $price);
      $insert->execute();
      $address = new Addresses($this->_dbHandle->query("SELECT * FROM `address` WHERE `id`=" . $this->_dbHandle->lastInsertId())->fetch());

      $this->_dbHandle->commit();
      return $address;
    } catch (PDOException $e) {
      echo "we rolled back address insertion into table";
      echo $e->getMessage();
      $this->_dbHandle->rollBack();
    }
  }
}