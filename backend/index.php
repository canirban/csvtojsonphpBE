<?php
 require __DIR__ . "/inc/bootstrap.php";
 header('Access-Control-Allow-Origin: *');

 header('Access-Control-Allow-Methods: POST');
 
 header("Access-Control-Allow-Headers: X-Requested-With");

 
include(dirname(__DIR__)."/backend/Controller/Api/ConverterController.php");

if($_SERVER['REQUEST_METHOD']=="POST" ){
    $converterController = new ConverterController();
    $converterController->postConversion();
   }else{
       header('HTTP/1.0 404 Not Found');
       echo "<h1>404 Not Found</h1> 
       The request is not valid.";
       exit();
   }


?>