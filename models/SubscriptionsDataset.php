<?php
require_once('models/Database.php');
require_once('models/Addresses.php');
class SubscriptionsDataset
{
  protected $_dbHandle, $_dbInstance;


  public function __construct()
  {
    //initiate the fields needed for the model
    $this->_dbInstance = Database::getInstance();
    $this->_dbHandle = $this->_dbInstance->getdbConnection();
  }
  //id, streetAddress , latitude, longitude, rate
  public function insertSubscription($streetAddress, $latitude, $longitude, $rate)
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


  //function to read all subscriptions for an owner's charge point
  public function readChargepointRenters($userId)
  {
    try {
      $sql = "SELECT * FROM subscriptions WHERE ownerId=?";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $userId);
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "couldnt read subscriptions for owner";
      echo $e->getMessage();
    }
  }

  //function to read number of subscriptions for an owner's charge point
  public function readNumberOfSubscriptions($userId)
  {
    try {
      $sql = "SELECT COUNT(*) FROM subscriptions WHERE ownerId=?";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $userId);
      $stmt->execute();
      return $stmt->fetchColumn();
    } catch (PDOException $e) {
      echo "couldnt read number of subscriptions for owner";
      echo $e->getMessage();
    }
  }

  //function to return subscriptions for owner according to pagination
  public function readChargepointRentersPagination($userId, $page_first_result, $results_per_page)
  {
    try {
      $sql = "SELECT * FROM subscriptions WHERE ownerId=? LIMIT " . $page_first_result . "," . $results_per_page;
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $userId);
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "couldnt read subscriptions for owner";
      echo $e->getMessage();
    }
  }

  //function to read all subscriptions for a renter - will return subscription primary key and the chargepoint's address primary key
  public function readRenterSubscriptions($userId)
  {
    try {
      $sql = "SELECT subscriptions.sid, users.ownerAddressFK AS addressId FROM subscriptions INNER JOIN users ON subscriptions.ownerId = users.id WHERE renterId=?";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $userId);
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "couldnt read subscriptions for renter";
      echo $e->getMessage();
    }
  }


}