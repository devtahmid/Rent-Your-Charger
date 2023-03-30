<?php
session_start();
require_once('model/Database.php');
require_once('model/Users.php');
require_once('model/UserDataset.php');
require_once('model/Addresses.php');
require_once('model/AddressDataset.php');



class Controllers {

    public $model;


    public function __construct() {

        $this->models = new Model();

    }


    public function invoke(){
        $result = $this->models->getLogin();
        //it will call the getlogin() function of models class and will store the return value

        if($result != 'invalid'){

            $_SESSION['valid']=$result;

            //echo '<script>alert("SUCCESS LOGIN!")</script>';

            header("Location: views/afterlogin.php");

        }else{
            header("Location: login404.php");

        }


    }

    public function invokeregisterasrenter(){


        $result = $this->models->registerasrenter();
        //it will call the invokeregisterasrenter() function of models class and will store the return value

        if($result == 'success'){

            header("Location: index.php");

        }else if($result == 'emailexists'){

            header("Location: emailvalidation.php");
        }
        else{
            header("Location: register404.php");

        }



    }



    public function invokeregisterasowner(){


        $result = $this->models->registerasowner();
        //it will call the invokeregisterasrenter() function of models class and will store the return value

        if($result == 'success'){

            header("Location: index.php");

        }else if($result == 'emailexists'){

            header("Location: emailvalidation.php");
        }else{
            header("Location: register404.php");

        }



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


?>