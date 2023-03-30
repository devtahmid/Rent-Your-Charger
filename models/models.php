<?php
require_once("model/models.php");



class Model
{

    public function getLogin()
    {
        $database = new mysqli("localhost", "root", "", "homechargerproj"); // DATABASE CONNECTION

        ///DATABASE QUERY CHECKS IF USER EXISTS

        if (isset($_REQUEST['loginnow'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $result = $database->query("select * from users where email='$email' and password='$password'");

            if ($result->num_rows == 1) {
                return $email;
            } else {

                return 'invalid';
            }
        }
    }



    public function registerasrenter()
    {

        ///DATABASE QUERY
        $database = new mysqli("localhost", "root", "", "homechargerproj"); // DATABASE CONNECTION


        if (isset($_REQUEST['registerasrenter'])) {

            $email = $_POST['email'];
            $name = $_POST['name'];
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];


            if ($password == $cpassword) {

                $result = $database->query("select * from users where email='$email';");
                if ($result->num_rows == 1) {
                    // MEANS THE EMAIL ALREADY EXISTS
                    // $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
                    return "emailexists";
                } else {

                    $database->query("insert into users(email,name,password,usertype,address,coordinates,price) values('$email','$name','$password','renter','n/a','n/a','n/a');");

                    return 'success';
                }
            } else {
                return 'passwordnotsame!';
            }
        }
    }




    public function registerasowner()
    {

        ///DATABASE QUERY
        $database = new mysqli("localhost", "root", "", "homechargerproj"); // DATABASE CONNECTION


        if (isset($_REQUEST['registerasowner'])) {

            $email = $_POST['email'];
            $name = $_POST['name'];
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];
            $location = $_POST['location'];
            $coordinates = $_POST['coordinates'];
            $price = $_POST['price'];


            if ($password == $cpassword) {

                $result = $database->query("select * from users where email='$email';");
                if ($result->num_rows == 1) {

                    return "emailexists";
                } else {

                    $database->query("insert into users(email,name,password,usertype,address,coordinates,price) values('$email','$name','$password','owner','$location','$coordinates','$price');");

                    return 'success';
                }
            } else {
                return 'passwordnotsame!';
            }
        }
    }



    ////////RETRIEVE ALL THE CHARGEPOINTS

    public function chargepoints()
    {

        ///DATABASE QUERY
        $database = new mysqli("localhost", "root", "", "homechargerproj"); // DATABASE CONNECTION


        if (isset($_REQUEST['search'])) {

            $chargepoints = $_POST['chargepoints'];



            if ($chargepoints == "chargepoints" || $chargepoints == "charge points" || $chargepoints == "charge" || $chargepoints == "points" || $chargepoints == "pointscharge") {

                return "true";
            } else {
                return 'false';
            }
        }
    }


    /// THIS FUNCTION DISPLAY THE INFORMATION OF SPECIFIC CHARGEPOINTS IF USER CLICKS.
    public function subscribe()
    {

        ///DATABASE QUERY
        $database = new mysqli("localhost", "root", "", "homechargerproj"); // DATABASE CONNECTION


        if (isset($_REQUEST['subscribe'])) {

            $subscribe = $_POST['subscribe'];

            $result = $database->query("select * from users where ID='$subscribe';");

            return $result;
        }
    }



    /// THIS FUNCTION IS WHEN THE USER SUBSCRIBE ANG FILL UP THE FORM FOR RENTING A CHARGE POINT AND SUBMIT.

    public function submitSubscribe()
    {

        $database = new mysqli("localhost", "root", "", "homechargerproj"); // DATABASE CONNECTION


        if (isset($_REQUEST['submitSubscribe'])) {

            $chargePointsID = $_POST['id'];
            $date = $_POST['date'] . "" . $_POST['time'];
            $duration = $_POST['duration'];
            $kwh = $_POST['kwh'];
            $user = $_POST['user'];
            $total = intval($_POST['price']) * intval($_POST['kwh']);

            $database->query("insert into subscribedchargepoints(chargepointsID,date,duration,kWh,total,user) values('$chargePointsID','$date','$duration','$kwh','$total','$user');");

            return 'success';
        } else {
            return 'error';
        }
    }
}
