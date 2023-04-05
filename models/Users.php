<?php
require_once('models/Database.php');
class Users
{
    protected $_id, $_name, $_email, $_password, $_isOwner, $_ownerAddressFK;
    public function __construct($dbRow)
    {
        $this->_id = $dbRow['id'];
        $this->_name = $dbRow['name'];
        $this->_email = $dbRow['email'];
        $this->_password = $dbRow['password'];
        $this->_isOwner = $dbRow['isOwner'];
        $this->_ownerAddressFK = $dbRow['ownerAddressFK'];
    }

    public function getID()
    {
        return $this->_id;
    }
    public function getName()
    {
        return $this->_name;
    }
    public function getEmail()
    {
        return $this->_email;
    }
    public function getPassword()
    {
        return $this->_password;
    }
    public function getIsOwner()
    {
        return $this->_isOwner;
    }
    public function getOwnerAddressFK()
    {
        return $this->_ownerAddressFK;
    }
}
