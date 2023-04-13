<?php
session_start();
require_once('models/Database.php');
require_once('models/Users.php');
require_once('models/UserDataset.php');
require_once('models/Addresses.php');
require_once('models/AddressDataset.php');



class Controllers {

    public $model;


    public function __construct() {

        $this->models = new Model();

    }


 

    public function chargepoints(){


        $result = $this->models->chargepoints();
        //it will call the invokeregisterasrenter() function of models class and will store the return value

        if($result == 'true'){

            header("Location: views/chargepoints.php");

        }else{
            header("Location: views/afterlogin.php");

        }



    }




    public function subscribe(){


        $result = $this->models->subscribe();
        //it will call the invokeregisterasrenter() function of models class and will store the return value

        return $result;



    }
    public function submitSubscribe(){


        $result = $this->models->submitSubscribe();
        //it will call the invokeregisterasrenter() function of models class and will store the return value

        if($result == 'success'){

            header("Location: views/afterlogin.php");

        }else{
            header("Location: subscribe.php");

        }




    }






}
