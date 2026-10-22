
<?php
require_once __DIR__ . '/config/db.php';

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$fullName = 'Administrator';

$stmt = $pdo->prepare(
    "SELECT id FROM users WHERE username = ?"
);
$stmt->execute([$username]);

$user = $stmt->fetch();

if ($user) {
    $stmt = $pdo->prepare(
        "UPDATE users SET password = ? WHERE username = ?"
    );
    $stmt->execute([$password, $username]);
} else {
    $stmt = $pdo->prepare(
        "INSERT INTO users (username, password, full_name, role)
         VALUES (?, ?, ?, 'admin')"
    );
    $stmt->execute([$username, $password, $fullName]);
}

echo "Admin account reset successfully!";
echo "<br>Username: admin";
echo "<br>Password: admin123";
?>
