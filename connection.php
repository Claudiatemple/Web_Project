<?php
    //The server name and connection
    define("SERVER_NAME","localhost");
    //The user of the system
    define("USERNAME","root");
    //This is the default password of usb
    define("DB_PASSWORD","usbw");
    //Name of the database that you are creating
    //The databse was created by the class of computer science year four and it was used in this system
    define("DATABASE_NAME","drug_system");

          //Create your variablres
    $con = new mysqli(SERVER_NAME, USERNAME, DB_PASSWORD, DATABASE_NAME);


    if(!$con){
        echo "<h2>Display Errors on Connecting to the database</h2>";
        die();
    }
?>