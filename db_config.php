<?php

$servername = "localhost";
$username = "root"; 
$password = "1802"; 
$dbname = "siaadatabase"; 

$conn = mysqli_connect("localhost", "root", "1802", "siaadatabase");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}else{
    echo "Connected successfully";
}

?>
