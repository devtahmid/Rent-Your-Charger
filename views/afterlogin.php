<?php

////CHECK IF THE USER IS AUTHORIZED
session_start();


if($_SESSION['valid']!=""){
}
else{
    header("location: ../index.php");
}

?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Borrow a Charge</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    
      <li class="nav-item">
      <form action="../triggerController.php" method ="POST">

        <button class="btn btn-light mt-3" type="submit" name="logout" >
      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
  <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
</svg></button>
</form>
      </li>

     
    </ul>

    <form class="form-inline my-2 my-lg-0" action="../triggerController.php" method ="POST"> 
      <input class="form-control mr-sm-2" type="search" name="chargepoints" placeholder="Search" aria-label="Search">

      <button class="btn btn-outline-success my-2 my-sm-0" name="search" type="submit">Search</button>

    </form>


    
  </div>
</nav>
<center>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<h1>SEARCH "Charge Points" to see the available Charge Points.</h1></center>