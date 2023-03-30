
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php


require_once('controller/controller.php');


$controllers = new Controllers();

$result = $controllers->subscribe();


  if($_SESSION['valid']!=""){
  }
  else{
    header("location: index.php");
  }




foreach($result as $row) {

?>


  <form action="triggerController.php" method="POST">
  <div class="container">
    <h1>Charge Points Details</h1>
    <p>Please fill in this form .</p>
    <hr>
    <input type="hidden"  value='.$_SESSION['valid'].' name="user"  >
    <input type="hidden"  value='.$row['ID'].' name="id"  >
    <input type="hidden"  value='.$row['price'].' name="price"  >

    <label for="email"><b>Charge Point Owner</b></label>
    <input type="text" placeholder="Enter Email" value='.$row['email'].' name="email" readOnly >

    <label for="psw"><b>Date & Time</b></label>
    <input type="date" name="date"  min="'.date('Y-m-d').'" required>

    <input type="time" name="time"  placeholder="Time" required><br>



    <br>
    <label for="psw-repeat"><b>'.$row['price'].' per kWh </b></label>

    <input type="number" placeholder="How many kWh?" name="kwh"  required >

    <br>
    <br>




    <label for="psw-repeat"><b>Duration</b></label>
    <input type="text" placeholder="How long?" name="duration" required  >

    <hr>

    <button type="submit"  name="submitSubscribe" class="registerbtn">SUBSCRIBE</button>
  </div>

  <div class="container signin">
    <p>Go back  <a href="views/afterlogin.php">Click this</a>.</p>
  </div>
</form>


<?php

}
?>

</body>
</html>

<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: black;
}

* {
  box-sizing: border-box;
}

/* Add padding to containers */
.container {
  padding: 16px;
  background-color: white;

}

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit button */
.registerbtn {
  background-color: #0697ff;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #f1f1f1;
  text-align: center;
}
</style>
