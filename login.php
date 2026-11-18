<?php
session_start();
include "./config/db.php";
$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($username === '' || $password === ''){
        $error = 'Please fill in all fields';
    }else{
        $result = $conn->execute_query("SELECT `id`, `username`, `password` 
        FROM `users` WHERE username = ?", [$username]);
        $user = $result->fetch_assoc();
        if($user && password_verify($password, $user['password'])){
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("location: index.php");
        exit();
        }else{
            $error = 'Username or password is incorrect';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b, #0f172a);
        }
        .login-card {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="card login-card p-4 shadow-lg">

        <div class="text-center mb-4">
            <i class="fa-solid fa-cart-shopping fa-3x mb-2"></i>
            <h4 class="fw-bold">Mini POS System</h4>
            <p class="mb-0 fs-6 fw-semibold text-secondary">WELCOME BACK</p>
        </div>

        <!-- Error message -->
        <?php if($error): ?>
        <div class="alert alert-danger py-2">
            <i class="fa-solid fa-circle-exclamation me-1"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input value="<?= htmlspecialchars($username ?? '') ?>" type="text" name="username" class="form-control" placeholder="Enter username" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                        <i class="fa-solid fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <p class="text-center mb-0"><a href="./register.php">Create an account</a></p>

            <button type="submit" class="btn btn-primary w-100 mt-3">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Login
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                passInput.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }
    </script>
</body>
</html>