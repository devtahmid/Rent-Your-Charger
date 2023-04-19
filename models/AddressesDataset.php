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

  //function to find matches for the street address renter gives as search input
  public function matchAddressByRenterInputStreetAddress($searchTerm, $rate, $page_first_result, $results_per_page)
  {
    try {
      $searchTerm = '%' . $searchTerm . '%';
      if ($results_per_page == 0) { //means i just need the count of the number of matches
        if ($rate == 0) { //not filtering by rate
          $sql = "SELECT * FROM addresses WHERE streetAddress LIKE ? ";
          $stmt = $this->_dbHandle->prepare($sql);
          $stmt->bindParam(1, $searchTerm);
        } else { // rate!=0 so we need to filter by rate
          $sql = "SELECT * FROM addresses WHERE streetAddress LIKE ? AND rate <= ? ORDER BY rate ASC";
          $stmt = $this->_dbHandle->prepare($sql);
          $stmt->bindParam(1, $searchTerm);
          $stmt->bindParam(2, $rate);
        }
      } else { //means i need the aactual mathces divided for pagination
        if ($rate == 0) {
          $sql = "SELECT * FROM addresses WHERE streetAddress LIKE ?  LIMIT " . $page_first_result . "," . $results_per_page;
          $stmt = $this->_dbHandle->prepare($sql);
          $stmt->bindParam(1, $searchTerm);
        } else { // rate!=0 so we need to filter by rate
          $sql = "SELECT * FROM addresses WHERE streetAddress LIKE ? AND rate <= ? ORDER BY rate ASC LIMIT " . $page_first_result . "," . $results_per_page;
          $stmt = $this->_dbHandle->prepare($sql);
          $stmt->bindParam(1, $searchTerm);
          $stmt->bindParam(2, $rate);
        }
      }

      $stmt->execute();

      if ($results_per_page == 0)
        return $stmt->rowCount();

      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "couldnt read addresses";
      echo $e->getMessage();
    }
  }

  //function to find chargepoints nearby for the coordinates input by renter
  public function matchAddressByRenterInputCoordinates($latitude, $longitude, $distance, $rate)
  {
    try {

      if ($rate == 0) {
        $sql = "SELECT *, ( 6371 * acos( cos( radians(:lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM addresses HAVING distance <= :distance ORDER BY distance";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->bindParam(':lat', $latitude);
        $stmt->bindParam(':lng', $longitude);
        $stmt->bindParam(':distance', $distance);
      } elseif ($rate != 0) { // need to filter by rate as well
        $sql = "SELECT *, ( 6371 * acos( cos( radians(:lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(:lng) ) + sin( radians(:lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM addresses HAVING distance <= :distance ORDER BY :rate";
        $stmt = $this->_dbHandle->prepare($sql);
        $stmt->bindParam(':lat', $latitude);
        $stmt->bindParam(':lng', $longitude);
        $stmt->bindParam(':distance', $distance);
        $stmt->bindParam(':rate', $rate);
      }


      $stmt->execute();
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "couldnt read addresses";
      echo $e->getMessage();
    }
  }
}
