<?php
include "./config/db.php";
$id = $_GET['id'];
$sql = "DELETE FROM `products` WHERE id = $id";
if (mysqli_query($conn, $sql)){
    header("location: products.php");
    exit();
}else{
    echo "Fail" . mysqli_connect_error($conn);
}

?>
