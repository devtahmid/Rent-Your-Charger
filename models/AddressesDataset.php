<?php
require_once('models/Database.php');
require_once('models/Addresses.php');
class AddressesDataset
{
  protected $_dbHandle, $_dbInstance;


  public function __construct()
  {
    //initiate the fields needed for the model
    $this->_dbInstance = Database::getInstance();
    $this->_dbHandle = $this->_dbInstance->getdbConnection();
  }
  //id, streetAddress , latitude, longitude, rate
  public function insertAddress($streetAddress, $latitude, $longitude, $rate)
  {
    try {
      $this->_dbHandle->beginTransaction();
      $sql = "INSERT INTO `addresses`(`streetAddress`, `latitude`, `longitude`, `rate`) VALUES (:streetAddress,:latitude,:longitude,:rate)";
      $insert = $this->_dbHandle->prepare($sql);
      $insert->bindParam(':streetAddress', $streetAddress);
      $insert->bindParam(':latitude', $latitude);
      $insert->bindParam(':longitude', $longitude);
      $insert->bindParam(':rate', $rate);
      $insert->execute();
      $address = new Addresses($this->_dbHandle->query("SELECT * FROM `addresses` WHERE `id`=" . $this->_dbHandle->lastInsertId())->fetch());

      $this->_dbHandle->commit();
      return $address;
    } catch (PDOException $e) {
      echo "we rolled back address insertion into table";
      echo $e->getMessage();
      $this->_dbHandle->rollBack();
    }
  }


  //function to read address row for a given id (foreign key in users table)
  public function readAddress($addressId)
  {
    try {
      $sql = "SELECT * FROM addresses WHERE id=?";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $addressId);
      $stmt->execute();
      return $stmt->fetch();
    } catch (PDOException $e) {
      echo "couldnt read address row";
      echo $e->getMessage();
    }
  }
}
