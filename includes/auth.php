<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Not logged in -> go to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>