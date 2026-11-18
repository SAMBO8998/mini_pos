<?php 
include "./config/db.php";
include "./includes/header.php";
$id = $_GET['id'];
$sql = "DELETE FROM `categories` WHERE id = $id";
if(mysqli_query($conn, $sql)){
    header("location: categories.php");
    exit();
}else{
    echo "Fail" . mysqli_error($conn);
}
?>




