<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['user'])) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT id, username, password, full_name, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        unset($user['password']);
        $_SESSION['user'] = $user;
        header('Location: index.php'); exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login | Mini Mart POS</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light d-flex align-items-center min-vh-100">
<div class="container"><div class="row justify-content-center"><div class="col-sm-9 col-md-5 col-lg-4">
<div class="card shadow-sm"><div class="card-body p-4"><h3 class="text-center mb-1">Mini Mart POS</h3><p class="text-center text-muted mb-4">Sign in to continue</p>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post"><div class="mb-3"><label class="form-label">Username</label><input class="form-control" name="username" required autofocus></div>
<div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
<button class="btn btn-primary w-100">Login</button></form>
<p class="small text-muted mt-3 mb-0">First login: admin / admin123</p></div></div></div></div></div></body></html>
