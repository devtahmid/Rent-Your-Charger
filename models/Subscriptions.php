<?php
require_once('models/Database.php');
class Subscriptions
{
    protected $_id, $_renterId, $_ownerId, $_startDate, $_startTime, $_endTime, $_status, $_cancelledDate;
    public function __construct($dbRow)
    {

        $this->_id = $dbRow['id'];
        $this->_renterId = $dbRow['renterId'];
        $this->_ownerId = $dbRow['ownerId'];
        $this->_startDate = $dbRow['startDate'];
        $this->_startTime = $dbRow['startTime'];
        $this->_endTime = $dbRow['endTime'];
        $this->_status = $dbRow['status'];
        $this->_cancelledDate = $dbRow['cancelledDate'];

    }

    public function getID()
    {
        return $this->_id;
    }
    public function getRenterId()
    {
        return $this->_renterId;
    }
    public function getOwnerId()
    {
        return $this->_ownerId;
    }
    public function getStartDate()
    {
        return $this->_startDate;
    }
    public function getStartTime()
    {
        return $this->_startTime;
    }
    public function getEndTime()
    {
        return $this->_endTime;
    }
    public function getStatus()
    {
        return $this->_status;
    }
    public function getCancelledDate()
    {
        return $this->_cancelledDate;
    }
}
