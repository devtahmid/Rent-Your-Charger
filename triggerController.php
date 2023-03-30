<?php
require_once('controller/controller.php'); 
$controllers = new Controllers();


if(isset($_REQUEST['registerasrenter'])){

$controllers->invokeregisterasrenter();

 }

elseif(isset($_REQUEST['registerasowner'])){

    $controllers->invokeregisterasowner();
    
 }
else if(isset($_REQUEST['loginnow'])){

$controllers->invoke();

}


else if(isset($_REQUEST['search'])){

$controllers->chargepoints();
    
    
}

else if(isset($_REQUEST['submitSubscribe'])){

    $controllers->submitSubscribe();
        
        
}


 else if(isset($_REQUEST['logout'])){
    header("Location: index.php");
    session_destroy();
  
}

else if(isset($_REQUEST['back'])){

    header("Location: views/afterlogin.php");

        
        }





?>
