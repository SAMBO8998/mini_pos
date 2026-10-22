<?php
$host = 'localhost';
$port = '3307'; // Change to 3306 if that is your MySQL port
$dbname = 'point_of_sale';
$username = 'root';
$password = ''; // XAMPP default is often blank

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    exit('Database connection failed. Check config/db.php and run setup.php. Details: ' . htmlspecialchars($e->getMessage()));
}
