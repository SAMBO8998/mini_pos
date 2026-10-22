<?php
// Run once in your browser: http://localhost/mini_mart_pos/setup.php
$host = '127.0.0.1';
$port = '3307'; // Change to 3306 if needed
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS point_of_sale CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE point_of_sale");

    $sql = [
      "CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('admin') NOT NULL DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS categories (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS products (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_id INT UNSIGNED NOT NULL,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL DEFAULT 0,
        stock INT UNSIGNED NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
          ON UPDATE CASCADE ON DELETE RESTRICT
      ) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS orders (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        receipt_no VARCHAR(50) NOT NULL UNIQUE,
        user_id INT UNSIGNED NOT NULL,
        total DECIMAL(10,2) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
          ON UPDATE CASCADE ON DELETE RESTRICT
      ) ENGINE=InnoDB",
      "CREATE TABLE IF NOT EXISTS order_items (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id BIGINT UNSIGNED NOT NULL,
        product_id INT UNSIGNED NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        quantity INT UNSIGNED NOT NULL,
        CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders(id)
          ON UPDATE CASCADE ON DELETE CASCADE,
        CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES products(id)
          ON UPDATE CASCADE ON DELETE RESTRICT
      ) ENGINE=InnoDB"
    ];
    foreach ($sql as $statement) $pdo->exec($statement);

    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute(['admin']);
    if (!$check->fetch()) {
        $insert = $pdo->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, 'admin')");
        $insert->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT), 'Administrator']);
    }

    echo '<!doctype html><html><head><meta charset="utf-8"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="container py-5">';
    echo '<div class="alert alert-success"><h3>Mini Mart POS setup complete!</h3><p>Database and tables are ready.</p><p><b>Login:</b> admin<br><b>Password:</b> admin123</p><a class="btn btn-primary" href="login.php">Go to login</a></div>';
    echo '<p class="text-danger">For safety, delete setup.php after successful setup.</p></body></html>';
} catch (PDOException $e) {
    exit('Setup failed: ' . htmlspecialchars($e->getMessage()));
}
