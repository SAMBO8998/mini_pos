<?php
include "./config/db.php";
$error = '';
$success = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    if($username === '' || $password === '' || $confirm === ''){
        $error = 'Please fill in all fields';
    }else if(strlen($username) < 3){
        $error = 'Username must be at least 3 characters';
    }else if(strlen($password) < 6){
        $error = 'Password must be at least 6 characters';
    }else if($confirm != $password){
        $error = 'Password and Confirm password do not match';
    }else{
        $check = $conn->prepare("SELECT `id` FROM `users` WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();
        if($check->num_rows > 0){
            $error = 'Please enter a different username';
        }else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO `users`(`username`, `password`) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hash);
            if($insert->execute()){
                $success = 'Registration successful. You can login now.';
            }else{
                $error = 'Please try again';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Management</title>
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
            <i class="fa-solid fa-user-plus fa-2x text-primary mb-2"></i>
            <h4 class="fw-bold">Create Account</h4>
            <p class="mb-0 fs-6 fw-semibold text-secondary">REGISTER</p>
        </div>

        <!-- alert danger -->
         <?php if($error): ?>
        <div class="alert alert-danger py-2">
            <i class="fa-regular fa-circle-xmark"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>
        <!-- alert success -->
         <?php if($success): ?>
         <div class="alert alert-success py-2">
            <i class="fa-solid fa-check"></i>
            <?= htmlspecialchars($success) ?>
         </div>
         <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input value="<?= htmlspecialchars($username ?? '') ?>" type="text" name="username" class="form-control border-secondary" placeholder="Enter username" required autofocus>
                
            </div>

            <div class="mb-3">
                <label for="" class="form-label">Password</label>
                <div class="input-group">
                    <input id="password" name="password" type="password" class="form-control border-secondary" placeholder="Enter password" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', 'icon1')">
                        <i class="fa-solid fa-eye" id="icon1"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm password</label>
                <div class="input-group">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control border-secondary" placeholder="Confirm Password" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password', 'icon2')">
                    <i class="fa-solid fa-eye" id="icon2"></i>
                </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">
                <i class="fa-solid fa-user-plus me-2"></i>Register
            </button>
        </form>

        <p class="text-center mt-3 mb-0">   
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
    <script>
        function togglePassword(Input_id, Icon_Id){
            const input = document.getElementById(Input_id);
            const icon = document.getElementById(Icon_Id);
            if (input.type === 'password'){
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            }else{
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }
    </script>
</body>
</html>

