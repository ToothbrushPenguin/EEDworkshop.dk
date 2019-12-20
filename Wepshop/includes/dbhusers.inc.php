<?php
 $servername = "localhost";
 $username = "root";
 $password = "root";
 $database = "users";
  
 $conn = new mysqli($servername, $username, $password, $database); //optretter forbinelsen

 if (!conn){
     die("connection failed: ".mysqli_connect_error());
 }

 ?>