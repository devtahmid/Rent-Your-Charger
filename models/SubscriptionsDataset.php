<?php
require_once('models/Database.php');
require_once('models/Addresses.php');
require_once('models/Subscriptions.php');
class SubscriptionsDataset
{
  protected $_dbHandle, $_dbInstance;


  public function __construct()
  {
    //initiate the fields needed for the model
    $this->_dbInstance = Database::getInstance();
    $this->_dbHandle = $this->_dbInstance->getdbConnection();
  }
  //id, renterId, ownerId, startDate, startTime, endTime, status
  public function insertSubscription($renterId, $ownerId, $startDate, $startTime, $endTime)
  {
    $status = "renting";
    try {
      $this->_dbHandle->beginTransaction();
      $sql = "INSERT INTO `subscriptions`(`renterId`, `ownerId`, `startDate`, `startTime`, `endTime`, `status`) VALUES (:renterId,:ownerId,:startDate,:startTime,:endTime,:status)";
      $insert = $this->_dbHandle->prepare($sql);
      $insert->bindParam(':renterId', $renterId);
      $insert->bindParam(':ownerId', $ownerId);
      $insert->bindParam(':startDate', $startDate);
      $insert->bindParam(':startTime', $startTime);
      $insert->bindParam(':endTime', $endTime);
      $insert->bindParam(':status', $status);
      $insert->execute();
      $subscription = new Subscriptions($this->_dbHandle->query("SELECT * FROM `subscriptions` WHERE `sid`=" . $this->_dbHandle->lastInsertId())->fetch());

      $this->_dbHandle->commit();
      return $subscription;
    } catch (PDOException $e) {
      echo "we rolled back subscription insertion into table";
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
  //function to read all subscriptions for a renter
  public function readRenterSubscriptions($userId)
  {
    try {
      $sql = "SELECT * FROM subscriptions WHERE renterId=? and status='renting'";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $userId);
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "couldnt read subscriptions for renter";
      echo $e->getMessage();
    }
  }

  //function to read all subscriptions for a renter - will return subscription primary key and the chargepoint's address primary key
  public function readRenterSubscriptionsWithAddressId($userId)
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

  //function to return all the occupied slots for a ownerId (chargepoint)
  public function readOccupiedSlots($ownerId)
  {
    try {
      $sql = "SELECT * FROM subscriptions WHERE ownerId=? AND status='renting'";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $ownerId);
      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "couldnt read occupied slots for chargepoint";
      echo $e->getMessage();
    }
  }

  //function to cancel a subscriotion
  public function cancelSubscription($sid)
  {
    try {
      $sql = "UPDATE subscriptions SET status='cancelled' WHERE sid=?";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(1, $sid);
      $stmt->execute();
      return $stmt->rowCount();
    } catch (PDOException $e) {
      echo "couldnt cancel subscription";
      echo $e->getMessage();
    }
  }

  //function to check if user's chosen time period and date clases with one of the occupied slots in the DB
  public function checkIfTimeClashes($ownerId, $startTime, $endTime, $date)
  {
    try {
      $sql = "SELECT * FROM subscriptions WHERE ownerId=:ownerId AND status='renting' AND ((startTime<=:startTime AND :startTime <=endTime) OR (startTime<=:endTime AND :endTime<=endTime) OR (:startTime<=startTime AND :endTime>=endTime)) AND startDate=:date ";
      $stmt = $this->_dbHandle->prepare($sql);
      $stmt->bindParam(':ownerId', $ownerId);
      $stmt->bindParam(':startTime', $startTime);
      $stmt->bindParam(':endTime', $endTime);
      $stmt->bindParam(':date', $date);
      $stmt->execute();
      $stmt->fetchAll();
      return $stmt->rowCount();
    } catch (PDOException $e) {
      echo "couldnt check if time clashes";
      echo $e->getMessage();
    }
  }
}
