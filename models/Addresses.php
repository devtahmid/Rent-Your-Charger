<?php
//address table has id, street, latitude, longitude, rate
require_once('models/Database.php');
class Addresses
{
    protected $_id, $_streetAddress, $_latitude, $_longitude, $_rate;
    public function __construct($dbRow)
    {
        $this->_id = $dbRow['id'];
        $this->_streetAddress = $dbRow['streetAddress'];
        $this->_latitude = $dbRow['latitude'];
        $this->_longitude = $dbRow['longitude'];
        $this->_rate = $dbRow['rate'];
    }

    public function getID()
    {
        return $this->_id;
    }
    public function getStreet()
    {
        return $this->_streetAddress;
    }
    public function getLatitude()
    {
        return $this->_latitude;
    }
    public function getLongitude()
    {
        return $this->_longitude;
    }
    public function getPrice()
    {
        return $this->_rate;
    }
}