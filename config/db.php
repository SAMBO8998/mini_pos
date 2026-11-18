<?php
$host = "localhost";
$username = "root";
$database = "point_of_sale";
$port = 3307;
$password = "";
$conn = mysqli_connect($host, $username,$password, $database, $port);
mysqli_set_charset($conn, "utf8mb4"); 
if (!$conn){
    die("falid" . mysqli_connect_error());
}
?>
